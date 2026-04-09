<?php

declare(strict_types=1);

namespace Bites\Service\Services;

use App\Models\User;
use Bites\Organization\Authorization\RoleMapper;
use Bites\Organization\Structure\OrgUnit;
use Bites\Employment\Models\Staff;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\PermissionRegistrar;

class RoleSyncService
{
    public function syncFromStaff(User $user, ?Staff $staff): void
    {
        if (! $staff instanceof Staff) {
            $this->removeStaffNamespaces($user);

            return;
        }

        $ouid = $staff->org_unit_id;
        $merged = array_values(array_unique(array_merge(
            RoleMapper::where('role_name', 'ut_staff')->pluck('id')->all(),
            $staff->staffRoleLinks->pluck('id')->all()
        )));
        app(PermissionRegistrar::class)->setPermissionsTeamId($ouid);
        $user->syncRoles($merged);
        app(PermissionRegistrar::class)->setPermissionsTeamId(null);

        // $targetGlobal = $this->computeGlobalRoles($staff); // ut_*, st_*, plus global OrgRole implied roles
        // $targetTeams = $this->computeTeamRoles($staff);   // ou_* roles per OrgUnit team (keyed by org_unit_id)

        // $this->syncGlobalNamespaces($user, $targetGlobal);
        // $this->syncTeamNamespaces($user, $targetTeams);
    }

    protected function computeGlobalRoles(Staff $staff): array
    {
        $roles = [];

        // Canonical staff roles
        $roles[] = 'ut_staff';

        if ($staff->employment_type === 'employee') {
            $roles[] = 'st_employee';
        } elseif ($staff->employment_type === 'contractor') {
            $roles[] = 'st_contractor';
        }

        if ($staff->is_ou_manager) {
            $roles[] = 'st_ou_manager';
        }

        $staff_roles = $staff->roles
            ->pluck('name')
            ->filter(fn ($name): bool => is_string($name) && (str_starts_with($name, 'st_') || str_starts_with($name, 'jt_')))
            ->values()
            ->all(); // array<string>

        // // JobPosition implied global roles
        // foreach ($staff->jobPosition?->orgRoles ?? [] as $or) {
        //     if ($or->scope === 'global') {
        //         $roles[] = $or->role_name;
        //     }
        // }
        $roles = array_merge($roles, $staff_roles);

        return array_values(array_unique($roles));
    }

    protected function computeTeamRoles(Staff $staff): array
    {
        $teams = [];

        // Team context is the OrgUnit
        $orgUnit = $staff->orgUnit;
        if ($orgUnit) {
            $teamId = $orgUnit->getKey();
            $teams[$teamId] = [];

            foreach ($staff->jobPosition?->orgRoles ?? [] as $or) {
                if ($or->scope === 'ou' && str_starts_with($or->role_name, 'ou_')) {
                    $teams[$teamId][] = $or->role_name;
                }
            }

            // If you store explicit OU role grants per Staff, merge them here:
            // $teams[$teamId] = array_merge($teams[$teamId], $staff->explicitOuRoleNames());
        }

        // unique per team
        foreach ($teams as $t => $names) {
            $teams[$t] = array_values(array_unique($names));
        }

        return $teams;
    }

    protected function syncGlobalNamespaces(User $user, array $target): void
    {
        $managedPrefixes = ['ut_', 'st_', 'jt_']; // plus any global implied names you want managed

        $current = $user->roles
            ->pluck('name')
            ->filter(fn (string $n): bool => $this->hasAnyPrefix($n, $managedPrefixes) || $this->isManagedGlobalImplied($n))
            ->values()
            ->all();

        $toAdd = array_values(array_diff($target, $current));
        $toRemove = array_values(array_diff($current, $target));

        foreach ($toAdd as $name) {
            if (! $user->hasRole($name)) {
                $user->assignRole($name);
            }
        }

        foreach ($toRemove as $name) {
            if ($user->hasRole($name)) {
                $user->removeRole($name);
            }
        }
    }

    /**
     * Team-aware sync using Spatie's team context (no raw SQL).
     *
     * - For each target team (OrgUnit), compute current ou_* roles by switching context,
     *   then add/remove diffs under that context.
     * - Determine "obsolete" team contexts by discovering all teams where the user currently
     *   holds ou_* roles (switching context across OrgUnits), then remove ou_* roles in teams
     *   that are no longer in the target map.
     */
    protected function syncTeamNamespaces(User $user, array $targetTeams): void
    {
        $managedPrefix = 'ou_';
        $permissionRegistrar = app(PermissionRegistrar::class);

        // 1) Apply diffs per target team
        foreach ($targetTeams as $teamId => $targetNames) {
            // Read current OU roles under this team context
            $permissionRegistrar->setPermissionsTeamId($teamId);
            $currentNames = $user->roles
                ->pluck('name')
                ->filter(fn ($n): bool => str_starts_with($n, $managedPrefix))
                ->values()
                ->all();

            $toAdd = array_values(array_diff($targetNames, $currentNames));
            $toRemove = array_values(array_diff($currentNames, $targetNames));

            foreach ($toAdd as $name) {
                if (! $user->hasRole($name)) {
                    $user->assignRole($name);
                }
            }

            foreach ($toRemove as $name) {
                if ($user->hasRole($name)) {
                    $user->removeRole($name);
                }
            }

            // unset context after each team
            $permissionRegistrar->setPermissionsTeamId(null);
        }

        // 2) Remove obsolete teams (teams where the user currently has ou_* roles
        //    but which are not present in $targetTeams; e.g., staff moved OrgUnit)
        $currentTeamIds = $this->discoverUserOuRoleTeamIds($user);  // no raw SQL
        $targetTeamIds = array_keys($targetTeams);
        $obsoleteTeamIds = array_values(array_diff($currentTeamIds, $targetTeamIds));

        foreach ($obsoleteTeamIds as $obsoleteTeamId) {
            $permissionRegistrar->setPermissionsTeamId($obsoleteTeamId);
            // Under this context, remove any ou_* roles
            $currentOuRoles = $user->roles
                ->pluck('name')
                ->filter(fn ($n): bool => str_starts_with($n, $managedPrefix))
                ->values()
                ->all();

            foreach ($currentOuRoles as $currentOuRole) {
                if ($user->hasRole($currentOuRole)) {
                    $user->removeRole($currentOuRole);
                }
            }

            $permissionRegistrar->setPermissionsTeamId(null);
        }
    }

    /**
     * Discover all team IDs (OrgUnit IDs) where the user currently holds any ou_* role.
     * This avoids raw SQL by iterating through OrgUnits and reading roles via team context.
     */
    protected function discoverUserOuRoleTeamIds(User $user): array
    {
        $permissionRegistrar = app(PermissionRegistrar::class);

        // If your OrgUnit table is large, consider scoping to relevant units only.
        $teamIds = OrgUnit::query()->pluck('id')->all();
        $result = [];

        foreach ($teamIds as $teamId) {
            $permissionRegistrar->setPermissionsTeamId($teamId);

            $hasOuRoles = $user->roles
                ->pluck('name')
                ->contains(fn ($n): bool => str_starts_with($n, 'ou_'));

            if ($hasOuRoles) {
                $result[] = (int) $teamId;
            }

            $permissionRegistrar->setPermissionsTeamId(null);
        }

        return array_values(array_unique($result));
    }

    protected function hasAnyPrefix(string $name, array $prefixes): bool
    {
        foreach ($prefixes as $prefix) {
            if (str_starts_with($name, $prefix)) {
                return true;
            }
        }

        return false;
    }

    protected function isManagedGlobalImplied(string $name): bool
    {
        // If you introduce global implied names outside ut_/st_, list them here, e.g. 'st_ou_manager'.
        return false;
    }

    /**
     * Remove ut_*, st_* globally and ALL ou_* roles across ALL teams
     * using Spatie team context (no raw SQL).
     */
    protected function removeStaffNamespaces(User $user): void
    {
        // Remove ut_*, st_* from the user (global)
        foreach ($user->roles as $role) {
            $name = $role->name;
            if (str_starts_with($name, 'ut_') || str_starts_with($name, 'st_') || str_starts_with($name, 'jt_')) {
                $user->removeRole($name);
            }
        }

        // Remove ou_* from the user under every team context
        $permissionRegistrar = app(PermissionRegistrar::class);
        $teamIds = OrgUnit::query()->pluck('id')->all();

        foreach ($teamIds as $teamId) {
            $permissionRegistrar->setPermissionsTeamId($teamId);

            $currentOuRoles = $user->roles
                ->pluck('name')
                ->filter(fn ($n): bool => str_starts_with($n, 'ou_'))
                ->values()
                ->all();

            foreach ($currentOuRoles as $currentOuRole) {
                if ($user->hasRole($currentOuRole)) {
                    $user->removeRole($currentOuRole);
                }
            }

            $permissionRegistrar->setPermissionsTeamId(null);
        }
    }

    public function attachRolesToAll(?array $onlyStaffIds = null, int $chunk = 500, bool $dryRun = false): void
    {
        $builder = Staff::query()
            // Load what your accessors need to avoid N+1.
            ->with('jobPosition:id,is_people_manager')
            ->select(['id', 'org_unit_id', 'job_position_id']); // trim columns

        if ($onlyStaffIds !== null && $onlyStaffIds !== []) {
            $builder->whereIn('id', $onlyStaffIds);
        }

        $processed = 0;

        $builder->chunkById($chunk, function ($staffChunk) use (&$processed): void {
            foreach ($staffChunk as $staff) {
                // Optional: per-staff transaction for atomicity.
                DB::transaction(function () use ($staff): void {
                    $this->attachRoles($staff);
                });

                $processed++;
                if ($processed % 200 === 0) {
                    Log::info(sprintf('RoleSyncService: processed %d staff...', $processed));
                }
            }
        });

        Log::info('RoleSyncService: finished. Total processed: '.$processed);
    }

    public function attachRoles(Staff $staff): void
    {
        //   $staff->staffRoleLinks()->detach();
        $ouid = $staff->org_unit_id;
        $ouRoleIds = collect();

        if ($ouid) {
            $ouRoleIds = $ouRoleIds->merge(
                RoleMapper::query()
                    ->where('role_name', 'ou_member')
                    ->where('org_unit_id', $ouid)
                    ->pluck('id')
            );

            if ($staff->is_people_manager) {
                $ouRoleIds = $ouRoleIds->merge(
                    RoleMapper::query()
                        ->where('role_name', 'ou_people_planner')
                        ->where('org_unit_id', $ouid)
                        ->pluck('id')
                );
            }
        }

        // Correctly group (st_% OR jt_%) AND enabled = 1
        $mappers = RoleMapper::query()
            ->where(function ($q): void {
                $q->where('role_name', 'like', 'st_%')
                    ->orWhere('role_name', 'like', 'jt_%');
            })
            ->where('enabled', true)
            ->get(['id', 'role_name', 'conditions', 'scope', 'category', 'label']);

        $stJtRoleIds = $mappers
            ->filter(function ($mapper) use ($staff): bool {
                $cond = (string) $mapper->conditions;

                return $cond !== '' && self::evaluateSimpleCondition($cond, $staff);
            })
            ->pluck('id');

        // Final, unique set of role_mapper IDs to be linked to this staff
        $finalIds = $ouRoleIds->merge($stJtRoleIds)->unique()->values()->all();

        // Idempotent: makes pivot exactly match $finalIds
        $staff->staffRoleLinks()->sync($finalIds);
    }

    public function attachRolesOld(Staff $staff): void
    {
        $mappers = RoleMapper::query()->where('role_name', 'like', 'st_%')->orWhere('role_name', 'like', 'jt_%')->where('enabled', true)->get(['id', 'role_name', 'conditions', 'scope', 'category', 'label']);

        if ($mappers->isEmpty()) {
            Log::debug('RoleSyncService: $mappers->isEmpty()');

            return;
        }

        $roleNamesToAttach = [];

        $ouid = $staff->org_unit_id;
        if ($ouid) {
            // app(\Spatie\Permission\PermissionRegistrar::class)->setPermissionsTeamId($ouid);
            // Log::debug($ouid . 'RoleSyncService' . $staff->name);
            $ouRoles[] = RoleMapper::query()->where('role_name', 'ou_member')->where('org_unit_id', $ouid)->pluck('id');
            // $staff->assignRole('ou_member');
            if ($staff->is_people_manager) {
                $ouRoles[] = RoleMapper::query()->where('role_name', 'ou_people_planner')->where('org_unit_id', $ouid)->pluck('id');
            }

            $staff->staffRoleLinks();
        }

        // app(\Spatie\Permission\PermissionRegistrar::class)->setPermissionsTeamId(null);
        //         if ($staff->is_people_manager) {
        //             $roleNames[] = 'ou_people_planner';
        //         }
        // $staff->assignRole();

        //         $role_ids = array_values(array_unique(array_merge(
        //             RoleMapper::query()
        //                 ->where('enabled', true)
        //                 ->where('role_name', 'like', 'ou_%')
        //                 ->where('org_unit_id', $staff->org_unit_id)
        //                 ->whereIn('role_name', $roleNames)
        //                 ->pluck('id')
        //                 ->all(),

        //             $staff->roles()->where('name', 'like', 'st_%')->pluck('roles.id')->all(),

        //             $staff->roles()->where('name', 'like', 'jt_%')->pluck('roles.id')->all(),
        //         )));

        foreach ($mappers as $mapper) {
            $cond = (string) $mapper->conditions;
            if ($cond === '') {
                // Log::debug($mapper->id . 'RoleSyncService: $cond === ""' . $staff->id);

                continue;
            }

            if (self::evaluateSimpleCondition($cond, $staff)) {
                // Log::debug($mapper->id . 'RoleSyncService: self::evaluateSimpleCondition($cond, $staff)' . $staff->id);
                $roleNamesToAttach[] = $mapper->id;
            }
        }

        if ($roleNamesToAttach === []) {
            // Log::debug($mapper->id . 'RoleSyncService: empty($roleNamesToAttach)' . $staff->id);

            return;
        }

        // Avoid duplicates — adjust based on your pivot schema
        $existing = $staff->roles()->where('name', 'like', 'st_%')->orWhere('name', 'like', 'jt_%')->pluck('roles.id')->all();

        $toAttach = array_values(array_diff($roleNamesToAttach, $existing)); // roles to add
        $toDetach = array_values(array_diff($existing, $roleNamesToAttach)); // roles to remove

        Log::debug('RoleSyncService: Linking roles', [
            'existing' => $existing,
            'toAttach' => $toAttach,
            'toDetach' => $toDetach,
            'staff_id' => $staff->id,
        ]);
        if ($toDetach !== []) {
            // $staff->roles()->detach($toDetach);
        }

        if ($toAttach !== []) {
            $staff->roles()->attach($toAttach);
        }

        // foreach ($roleNamesToAttach as $roleId) {
        //     $staff->roles()->attach($roleId);
        // }
    }

    /**
     * Evaluate a RoleMapper condition like:
     *   'shift_code=R-*'
     *   'shift_code=*-6G4S'
     *   'shift_category=T'
     * Supports '*' wildcard only on the right-hand value.
     */
    protected function evaluateSimpleCondition(string $condition, Staff $staff): bool
    {
        $condition = trim($condition);

        // Split once on '='
        $parts = explode('=', $condition, 2);
        if (count($parts) !== 2) {
            Log::warning('RoleMapper condition invalid', ['condition' => $condition]);

            return false;
        }

        [$field, $value] = [trim($parts[0]), trim($parts[1])];
        // Generic fallback if you allow other fields
        $actual = (string) $staff->getStaffAttributeValue($field) ?? null;

        // If the value contains '*' treat as wildcard; otherwise do strict equality (case-insensitive)
        if (str_contains($value, '*')) {
            return self::wildcardMatch($value, $actual);
        }

        // Log::Debug('evaluateSimpleCondition', ['field' => $field, 'value' => $value]);

        // Case-insensitive exact match
        return strtoupper(trim($actual)) === strtoupper(trim($value));
    }

    protected function wildcardMatch(string $pattern, ?string $actual): bool
    {
        if ($actual === null || trim($actual) === '') {
            return false;
        }

        // Normalize to uppercase and trim
        $pattern = strtoupper(trim($pattern));
        $actual = strtoupper(trim($actual));

        // Convert pattern to regex: escape, then '*' -> '.*'
        $regex = '/^'.str_replace('\*', '.*', preg_quote($pattern, '/')).'$/i';
        // Log::Debug('wildcardMatch', ['pattern' => $pattern, 'actual' => $actual, 'regex' => $regex]);

        return (bool) preg_match($regex, $actual);
    }
}

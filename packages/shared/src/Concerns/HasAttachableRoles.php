<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Models\Core\OrgUnit;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Traits\HasRoles;

trait HasAttachableRoles
{
    use HasRoles;

    /** Morph name used by the pivot. */
    protected function getAttachableMorphName(): string
    {
        return property_exists($this, 'attachableMorphName') && is_string($this->attachableMorphName)
            ? $this->attachableMorphName
            : 'attachable';
    }

    /** Polymorphic roles pivot (attachable_roles) with optional team_id column. */
    public function attachableRoles(): MorphToMany
    {
        return $this->morphToMany(
            Role::class,
            $this->getAttachableMorphName(),
            'attachable_roles',
            'attachable_id',
            'role_id'
        )
            ->withTimestamps()
            ->withPivot('team_id');
    }

    /**
     * PUBLIC API: Attach roles from a mixed payload.
     *
     * Supported formats (per item):
     *  - 'admin'                 // role name
     *  - 5                       // role id
     *  - ['name' => 'admin', 'team_id' => 10]
     *  - ['id' => 5, 'team_id' => null]
     *
     * Options:
     *  - ['sync_per_team' => bool]  // if true, replace only within the same team_id; otherwise incremental attach.
     */
    public function attachRolesFromMixed(null|array|string|int $rolesInput, array $options = []): void
    {
        if (in_array($rolesInput, ['', '0', 0, [], null], true)) {
            return;
        }

        $syncPerTeam = (bool) ($options['sync_per_team'] ?? false);

        // Normalize
        $normalized = $this->normalizeRolesInput($rolesInput);

        // Group into names vs ids by team_id
        $byTeamNames = []; // team_id => [names...]
        $byTeamIds = []; // team_id => [ids...]
        foreach ($normalized as $n) {
            $teamId = $n['team_id'] ?? null;

            if (isset($n['name'])) {
                $byTeamNames[$teamId] ??= [];
                $byTeamNames[$teamId][] = (string) $n['name'];
            } elseif (isset($n['id'])) {
                $byTeamIds[$teamId] ??= [];
                $byTeamIds[$teamId][] = (int) $n['id'];
            }
        }

        // Resolve names -> IDs and attach
        foreach ($byTeamNames as $teamIdKey => $names) {
            $teamId = $this->normalizeTeamId($teamIdKey);
            $uniqueNames = array_values(array_unique($names));
            if ($uniqueNames === []) {
                continue;
            }

            $ids = Role::query()
                ->whereIn('name', $uniqueNames)
                ->pluck('id')
                ->all();

            if (! empty($ids)) {
                $this->attachRoleIdsForTeam($ids, $teamId, $syncPerTeam);
            }
        }

        // Attach explicit IDs
        foreach ($byTeamIds as $teamIdKey => $ids) {
            $teamId = $this->normalizeTeamId($teamIdKey);
            $ids = array_values(array_unique($ids));
            if ($ids !== []) {
                $this->attachRoleIdsForTeam($ids, $teamId, $syncPerTeam);
            }
        }
    }

    protected function normalizeTeamId(mixed $teamId): ?int
    {
        if ($teamId === '' || $teamId === null) {
            return null;
        }

        if (is_int($teamId)) {
            return $teamId;
        }

        if (is_string($teamId)) {
            // dd($teamId);
        }

        if (is_numeric($teamId)) {
            return (int) $teamId;
        }

        // Non-numeric values are treated as "no team"
        return null;
    }

    /**
     * INTERNAL: Normalize roles payload into array of ['name'| 'id', 'team_id'?].
     */
    protected function normalizeRolesInput(null|array|string|int $rolesInput): array
    {
        $normalized = [];

        foreach ((array) $rolesInput as $item) {
            if (is_string($item)) {
                $normalized[] = ['name' => $item, 'team_id' => null];

                continue;
            }

            if (is_int($item)) {
                $normalized[] = ['id' => $item, 'team_id' => null];

                continue;
            }

            if (is_array($item)) {
                $name = Arr::get($item, 'name');
                $id = Arr::get($item, 'id');
                $teamId = $this->normalizeTeamId(Arr::get($item, 'team_id'));

                if ($name !== null) {
                    $normalized[] = ['name' => (string) $name, 'team_id' => $teamId];

                    continue;
                }

                if ($id !== null) {
                    $normalized[] = ['id' => (int) $id, 'team_id' => $teamId];

                    continue;
                }
            }

            Log::warning(
                'HasAttachableRoles: Unrecognized role item format',
                ['item' => $item]
            );
        }

        return $normalized;
    }

    /**
     * INTERNAL: Attach a set of role IDs for a single team context.
     * - If $syncPerTeam = true, replaces only rows for that team_id.
     * - Else, incremental: does not detach others.
     */
    protected function attachRoleIdsForTeam(array $ids, ?int $teamId, bool $syncPerTeam = false): void
    {
        if ($ids === []) {
            return;
        }

        // Build pivot map with 'team_id'
        $map = [];
        foreach ($ids as $id) {
            $map[$id] = ['team_id' => $teamId];
        }

        if ($syncPerTeam) {
            // Detach only existing rows for this team, then attach the new set
            $this->attachableRoles()->wherePivot('team_id', $teamId)->detach();
            $this->attachableRoles()->attach($map);
        } else {
            // Incremental attach (keeps others, preserves other teams)
            $this->attachableRoles()->syncWithoutDetaching($map);
        }
    }

    /** If you still want direct entry points by names/ids: */
    public function attachRolesByNames(array $names, ?int $teamId = null): void
    {
        $ids = Role::query()->whereIn('name', $names)->pluck('id')->all();
        $this->attachRoleIdsForTeam($ids, $teamId, false);
    }

    public function attachRolesByIds(array $ids, ?int $teamId = null): void
    {
        $this->attachRoleIdsForTeam($ids, $teamId, false);
    }

    /** Team-scoped sync (replace only rows with that team_id). */
    public function syncRolesByNamesForTeam(array $names, ?int $teamId = null): void
    {
        $ids = Role::query()->whereIn('name', $names)->pluck('id')->all();
        $this->attachRoleIdsForTeam($ids, $teamId, true);
    }

    public function syncRolesByIdsForTeam(array $ids, ?int $teamId = null): void
    {
        $this->attachRoleIdsForTeam($ids, $teamId, true);
    }

    /** Scope: visible to a user by GLOBAL roles (no team scoping). */
    public function scopeVisibleToUser(Builder $builder, $user): Builder
    {
        if (! $user) {
            return $builder->whereRaw('1=0');
        }

        $roleNames = $user->roles->pluck('name');
        $builder = $this->maybeFilterActive($builder);

        return $builder->whereHas('attachableRoles', fn ($rq) => $rq->whereIn('name', $roleNames));
    }

    /** Scope: visible to user within a specific OrgUnit using team-aware roles. */
    public function scopeVisibleToUserInOrgUnit(Builder $builder, $user, OrgUnit $orgUnit): Builder
    {
        if (! $user || ! $orgUnit) {
            return $builder->whereRaw('1=0');
        }

        /** @var PermissionRegistrar $permissionRegistrar */
        $permissionRegistrar = app(PermissionRegistrar::class);

        // Team-scoped roles (ou_*)
        $permissionRegistrar->setPermissionsTeamId($orgUnit->getKey());

        $teamRoleNames = $user->roles->pluck('name')
            ->filter(fn ($n): bool => str_starts_with($n, 'ou_'))
            ->values()->all();

        // Global roles (non ou_*)
        $permissionRegistrar->setPermissionsTeamId(null);
        $globalRoleNames = $user->roles->pluck('name')
            ->filter(fn ($n): bool => ! str_starts_with($n, 'ou_'))
            ->values()->all();

        $builder = $this->maybeFilterActive($builder);

        return $builder->where(function (Builder $builder) use ($globalRoleNames, $teamRoleNames, $orgUnit): void {
            $builder->whereHas('attachableRoles', fn ($rq) => $rq->whereIn('roles.name', $globalRoleNames))
                ->orWhereHas('attachableRoles', fn ($rq) => $rq
                    ->whereIn('roles.name', $teamRoleNames)
                    ->wherePivot('team_id', $orgUnit->getKey()));
        });
    }

    /** Alternative team-aware scope ignoring pivot team_id (derive from user’s context only). */
    public function scopeVisibleToUserByUserTeams(Builder $builder, $user, OrgUnit $orgUnit): Builder
    {
        if (! $user || ! $orgUnit) {
            return $builder->whereRaw('1=0');
        }

        /** @var PermissionRegistrar $permissionRegistrar */
        $permissionRegistrar = app(PermissionRegistrar::class);

        $permissionRegistrar->setPermissionsTeamId($orgUnit->getKey());

        $teamRoleNames = $user->roles->pluck('name')
            ->filter(fn ($n): bool => str_starts_with($n, 'ou_'))
            ->values()->all();

        $permissionRegistrar->setPermissionsTeamId(null);
        $globalRoleNames = $user->roles->pluck('name')
            ->filter(fn ($n): bool => ! str_starts_with($n, 'ou_'))
            ->values()->all();

        $effective = array_values(array_unique(array_merge($teamRoleNames, $globalRoleNames)));
        $builder = $this->maybeFilterActive($builder);

        return $builder->whereHas('attachableRoles', fn ($rq) => $rq->whereIn('roles.name', $effective));
    }

    /** If table has is_active, filter to true. */
    protected function maybeFilterActive(Builder $builder): Builder
    {
        $table = $builder->getModel()->getTable();
        if (Schema::hasColumn($table, 'is_active')) {
            return $builder->where($table.'.is_active', true);
        }

        return $builder;
    }
}

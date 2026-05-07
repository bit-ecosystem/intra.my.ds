<?php

declare(strict_types=1);

namespace Bites\Service\Services;

use Bites\Employment\Models\Staff;
use Bites\Organization\Structure\JobPosition;
use Bites\Organization\Structure\OrgUnit;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class StaffImportService
{
    /**
     * Import staff data:
     *  - PASS 1: OrgUnit, JobPosition (by stable code), Staff, and Person Attributes
     *  - PASS 2: Set JobPosition.superior_id based on superior_id (manager's staff_number), resolved via Staff->job_position_id
     *  - PASS 3: Recompute OrgUnit owners (overwrite owner_id)
     *
     * @param  array<int, array<string, mixed>>  $rows  Transformed array (WeaverToStaff output)
     * @param  array<string, mixed>  $meta  Context (source, timestamp, api_data_id, etc.)
     * @param  int  $chunk  Chunk size for PASS 3 (owner recompute)
     * @return array<string, int> Summary counters
     */
    public function import(array $rows, array $meta = [], int $chunk = 1000): array
    {
        $counters = [
            'org_units_checked' => 0,
            'job_positions_checked' => 0,
            'staff_checked' => 0,
            'org_units_created' => 0,
            'job_positions_created' => 0,
            'staff_created' => 0,
            'staff_updated' => 0,
            'attributes_saved' => 0,
            'superiors_linked' => 0,
            'org_owners_updated' => 0,
            'org_owners_eligible' => 0,
            'org_owners_skipped_same_org' => 0,
            'org_owners_skipped_no_org' => 0,
        ];

        if ($rows === []) {
            Log::info('StaffImportService: No rows to import', ['meta' => $meta]);

            return $counters;
        }

        // ---------- PASS 1: Upsert org units, job positions (by stable code), staff, and attributes ----------
        foreach ($rows as $item) {
            Log::debug('StaffImportService: Pass1 '.$item['name']);
            if (! is_array($item)) {
                continue;
            }

            $staffNumber = trim((string) ($item['staff_number'] ?? ''));
            if ($staffNumber === '') {
                // Same as seeder: staff_number is required to create Staff
                continue;
            }

            // Excel serial date → Carbon timestamp (same formula as seeder)
            $createdAt = $this->resolveCreatedAt($item, $meta);

            // OrgUnit
            $orgUnitName = trim((string) ($item['org_unit'] ?? ''));
            $counters['org_units_checked']++;
            $orgUnit = OrgUnit::firstOrCreate(
                ['name' => $orgUnitName],
                ['description' => $orgUnitName, 'created_at' => $createdAt]
            );
            if ($orgUnit->wasRecentlyCreated) {
                $counters['org_units_created']++;
            }

            // JobPosition by stable code (UUID)
            $jobTitle = trim((string) ($item['job_title'] ?? ''));
            $jobCode = trim((string) ($item['job_position'] ?? ''));

            if ($jobCode === '') {
                // If upstream ever fails to provide the stable code, skip (or derive deterministically if you prefer).
                Log::warning('StaffImportService: Missing job_position code (stable UUID). Skipping row.', [
                    'staff_number' => $staffNumber,
                    'job_title' => $jobTitle,
                ]);

                continue;
            }

            // Upsert JobPosition by code
            $counters['job_positions_checked']++;
            $jobPosition = JobPosition::updateOrCreate(
                ['code' => $jobCode],
                [
                    'title' => $jobTitle,
                    'org_unit_id' => $orgUnit->id,
                    'created_at' => $createdAt, // set on create; updateOrCreate won't change existing created_at
                ]
            );
            if ($jobPosition->wasRecentlyCreated) {
                $counters['job_positions_created']++;
            }

            // Ensure Staff points to this JobPosition
            $counters['staff_checked']++;
            $staff = Staff::updateOrCreate(
                ['staff_number' => $staffNumber],
                [
                    'name' => $item['name'] ?? null,
                    'org_unit_id' => $orgUnit->id,
                    'job_position_id' => $jobPosition->id,
                    'created_at' => $item['join_date'] ?? $createdAt,
                    'updated_at' => now(),
                ]
            );
            $staff->wasRecentlyCreated
                ? $counters['staff_created']++
                : $counters['staff_updated']++;

            // Save extra attributes (polymorphic relation), same keys & behavior
            $extraFields = [
                'login' => $item['login'] ?? null,
                'join_date' => $item['join_date'] ?? null,
                'company_email' => $item['company_email'] ?? null,
                'ldap' => $item['ldap'] ?? null,
                'shift_code' => $item['shift_code'] ?? null,
                'staff_category' => $item['staff_category'] ?? null,
                'job_category' => $item['job_category'] ?? null,
                'gender' => $item['gender'] ?? null,
                'federated_id' => $item['federated_id'] ?? null,
            ];

            foreach ($extraFields as $key => $value) {
                if (! empty($value)) {
                    $staff->personAttributes()->updateOrCreate(
                        ['key' => $key],
                        ['value' => $value]
                    );
                    $counters['attributes_saved']++;
                }
            }
        }

        // ---------- PASS 2: Link JobPosition.superior_id using Staff->job_position_id ----------
        foreach ($rows as $item) {
            Log::debug('StaffImportService: Pass2 '.$item['name']);
            $managerStaffNumber = isset($item['superior_id']) ? trim((string) $item['superior_id']) : null;

            if ($managerStaffNumber === null || $managerStaffNumber === '') {
                continue;
            }

            $managerStaff = Staff::where('staff_number', $managerStaffNumber)->first();
            if (! $managerStaff || ! $managerStaff->job_position_id) {
                // Manager might not exist in this batch or lacks a job_position
                continue;
            }

            $currentStaffNumber = trim((string) ($item['staff_number'] ?? ''));
            if ($currentStaffNumber === '') {
                continue;
            }

            $currentStaff = Staff::where('staff_number', $currentStaffNumber)->first();
            if (! $currentStaff || ! $currentStaff->job_position_id) {
                continue;
            }

            $currentJobPosition = JobPosition::find($currentStaff->job_position_id);
            $managerJobPosition = JobPosition::find($managerStaff->job_position_id);

            if (! $currentJobPosition || ! $managerJobPosition) {
                continue;
            }

            if ($currentJobPosition->superior_id !== $managerJobPosition->id) {
                $currentJobPosition->superior_id = $managerJobPosition->id;
                $currentJobPosition->save();
                $counters['superiors_linked']++;

                // Log::debug('StaffImportService: Linked superior', [
                //     'staff_number'            => $currentStaffNumber,
                //     'manager_staff_number'    => $managerStaffNumber,
                //     'current_job_position_id' => $currentJobPosition->id,
                //     'manager_job_position_id' => $managerJobPosition->id,
                // ]);
            }
        }

        // ---------- PASS 3: Assign roles to staff accordingly ----------

        Log::debug('StaffImportService: Pass3 '.$item['name']);
        app(RoleSyncService::class)->attachRolesToAll();

        Log::info('StaffImportService: Import completed', [
            'meta' => $meta,
            'counters' => $counters,
        ]);

        return $counters;
    }

    /**
     * Excel serial → Carbon (same as seeder)
     */
    protected function resolveCreatedAt(array $item, array $meta): Carbon
    {
        $createdSerial = $item['Created'] ?? null;

        if ($createdSerial) {
            // Excel 1900-based serial date with leap-year bug (subtract 2 days)
            $baseDate = Carbon::create(1900, 1, 1);
            $days = floor($createdSerial);
            $seconds = ($createdSerial - $days) * 86400;

            return $baseDate->copy()->addDays($days - 2)->addSeconds($seconds);
        }

        // Fallback: meta timestamp if provided
        if (! empty($meta['timestamp'])) {
            try {
                return Carbon::parse($meta['timestamp']);
            } catch (\Throwable $e) {
                // ignore parsing error; use now()
            }
        }

        return now();
    }

    /**
     * Overwrite org unit owner_id from JobPositions when superior's org unit
     * is NOT the same as the JobPosition's org unit.
     *
     * @param  int  $chunk  How many JobPositions per chunk
     * @return array<string, int> Summary
     */
    protected function assignOrgUnitOwners(int $chunk = 1000): array
    {
        $updated = 0;
        $eligible = 0;
        $skippedSameOrg = 0;
        $skippedNoOrgUnit = 0;

        JobPosition::with(['orgUnit', 'superior.orgUnit'])
            ->whereHas('orgUnit')
            ->orderBy('id')
            ->chunk($chunk, function ($positions) use (&$updated, &$eligible, &$skippedSameOrg, &$skippedNoOrgUnit): void {
                foreach ($positions as $position) {
                    $orgUnit = $position->orgUnit;

                    if (! $orgUnit) {
                        $skippedNoOrgUnit++;

                        continue;
                    }

                    $superiorOrgUnit = $position->superior?->orgUnit;

                    $isSameOrgUnit = $superiorOrgUnit && $superiorOrgUnit->is($orgUnit);
                    if ($isSameOrgUnit) {
                        $skippedSameOrg++;

                        continue;
                    }

                    $eligible++;
                    $orgUnit->update(['owner_id' => $position->id]);
                    $updated++;
                }
            });

        Log::info('StaffImportService: OrgUnit owner reassignment (overwrite) complete', [
            'eligible' => $eligible,
            'updated' => $updated,
            'skipped_same_org' => $skippedSameOrg,
            'skipped_no_org' => $skippedNoOrgUnit,
        ]);

        return [
            'eligible' => $eligible,
            'updated' => $updated,
            'skippedSameOrg' => $skippedSameOrg,
            'skippedNoOrgUnit' => $skippedNoOrgUnit,
        ];
    }
}

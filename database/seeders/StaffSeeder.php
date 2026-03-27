<?php

namespace Database\Seeders;

use Bites\Core\Organization\Models\JobPosition;
use Bites\Core\Organization\Models\OrgUnit;
use Bites\Hrm\Models\Staff;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class StaffSeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = base_path('database/factories/Hrm/staff.json');
        $data = json_decode(File::get($jsonPath), true);

        // ---------- PASS 1: Create org units, job positions, staff, and attributes ----------
        foreach ($data as $item) {
            // Convert Excel serial date to Carbon timestamp
            $createdSerial = $item['Created'] ?? null;
            $createdAt = now();

            if ($createdSerial) {
                $baseDate = Carbon::create(1900, 1, 1);
                $days = floor($createdSerial);
                $seconds = ($createdSerial - $days) * 86400;
                $createdAt = $baseDate->copy()->addDays($days - 2)->addSeconds($seconds);
            }

            // First or create OrgUnit
            $orgUnit = OrgUnit::firstOrCreate(
                ['name' => $item['org_unit']],
                [
                    'description' => $item['org_unit'],
                    'created_at' => $createdAt,
                ]
            );
            // echo "✅ Seeded {$orgUnit->id}\n";
            // First or create JobPosition
            $jobPosition = JobPosition::firstOrCreate(
                ['code' => $item['job_position']],
                [
                    'title' => $item['job_title'],
                    'org_unit_id' => $orgUnit->id,
                    'created_at' => $createdAt,
                ]
            );

            // Create Staff
            $staff = Staff::updateOrCreate(
                ['staff_number' => $item['staff_number']],
                [
                    'org_unit_id' => $orgUnit->id,
                    'job_position_id' => $jobPosition->id,
                    'created_at' => $createdAt,
                    'updated_at' => now(),
                ]
            );

            // Save extra attributes in polymorphic relation
            $extraFields = [
                'login' => $item['login'] ?? null,
                'nickname' => $item['nickname'] ?? null,
                'company_email' => $item['company_email'] ?? null,
                'ldap' => $item['ldap'] ?? null,
                'gender' => $item['gender'] ?? null,
                'federated_id' => $item['federated_id'] ?? null,
                // 'manager'       => $item['Manager'] ?? null,
            ];

            foreach ($extraFields as $key => $value) {
                if (! empty($value)) {
                    $staff->personAttributes()->updateOrCreate(
                        ['key' => $key],
                        ['value' => $value]
                    );
                }
            }
        }

        // ---------- PASS 2: Set superior_id on JobPosition based on JSON "User ID" ----------
        foreach ($data as $item) {
            // Manager staff_number is taken from JSON "User ID"
            $managerStaffNumber = isset($item['SuperiorID']) ? trim((string) $item['SuperiorID']) : null;
            if (! $managerStaffNumber) {
                continue;
            }
            $managerStaff = Staff::where('staff_number', $managerStaffNumber)->first();
            if (! $managerStaff || ! $managerStaff->job_position_id) {
                // Manager staff not found or no job_position_id assigned yet
                continue;
            }

            $currentJobPosition = JobPosition::where('code', $item['job_position'])->first();

            $managerJobPosition = JobPosition::find($managerStaff->job_position_id);
            if (! $managerJobPosition) {
                continue;
            }

            // Avoid self-loop and redundant updates
            if (
                $currentJobPosition->superior_id !== $managerJobPosition->id
            ) {

                $currentJobPosition->superior_id = $managerJobPosition->id;
                $currentJobPosition->save();
            }
        }

        // ---------- PASS 3: Recompute org unit owners ----------
        // Overwrite owner_id even if it already exists.
        $this->assignOrgUnitOwners(chunk: 1000);
    }

    /**
     * Overwrite org unit owner_id from JobPositions when superior's org unit
     * is NOT the same as the JobPosition's org unit.
     *
     * @param  int  $chunk  How many JobPositions per chunk
     */
    protected function assignOrgUnitOwners(int $chunk = 1000): void
    {
        $updated = 0;
        $eligible = 0;
        $skippedSameOrg = 0;
        $skippedNoOrgUnit = 0;

        JobPosition::with(['orgUnit', 'superior.orgUnit'])
            ->whereHas('orgUnit')
            ->orderBy('id')
            ->chunk($chunk, function ($positions) use (&$updated, &$eligible, &$skippedSameOrg, &$skippedNoOrgUnit) {
                foreach ($positions as $jp) {
                    $orgUnit = $jp->orgUnit;

                    if (! $orgUnit) {
                        $skippedNoOrgUnit++;

                        continue;
                    }

                    $superiorOrgUnit = $jp->superior?->orgUnit;

                    // Only skip when superior has an org unit AND it's exactly the same org unit
                    $isSameOrgUnit = $superiorOrgUnit && $superiorOrgUnit->is($orgUnit);
                    if ($isSameOrgUnit) {
                        $skippedSameOrg++;

                        continue;
                    }

                    // Eligible: different org unit (or superior missing org unit)
                    $eligible++;

                    // Always overwrite owner_id to ensure correct owner after seeding
                    $orgUnit->update(['owner_id' => $jp->id]);
                    $updated++;
                }
            });

        // Optional: Show summary during seeding
        if (property_exists($this, 'command') && $this->command) {
            $this->command->info('OrgUnit owner reassignment (overwrite) complete.');
            $this->command->line("Eligible: {$eligible}");
            $this->command->line("Updated (overwritten): {$updated}");
            $this->command->line("Skipped (same as superior org): {$skippedSameOrg}");
            $this->command->line("Skipped (missing org unit): {$skippedNoOrgUnit}");
        }
    }
}

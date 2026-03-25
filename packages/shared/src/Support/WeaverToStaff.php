<?php

declare(strict_types=1);

namespace Bites\Shared\Support;

use App\Models\RoleMapper;
use Bites\Shared\Models\ApiData;
use Database\Seeders\StaffSeeder;
use Illuminate\Support\Facades\Log;

class WeaverToStaff
{
    /**
     * Process a newly-created ApiData record: transform, filter, build meta, invoke seeder.
     */
    public static function process(ApiData $apiData): void {}

    /**
     * Bulk transform all incoming "weaver" records to StaffSeeder schema.
     */
    public static function transformAll(array $records): array
    {
        return array_values(array_map([self::class, 'transform'], $records));
    }

    /**
     * Transform a single "weaver" record into the shape expected by StaffSeeder.
     */
    public static function transform(array $rec): array
    {

        // 1) Determine job_title
        $rawJobTitle = isset($rec['JobTitle']) ? trim((string) $rec['JobTitle']) : '';
        if ($rawJobTitle !== '') {
            $jobTitle = str_replace(' ', '_', trim(preg_replace('/\s+/', ' ', $rawJobTitle)));
        } else {
            $lastName = isset($rec['LastName']) ? trim((string) $rec['LastName']) : '';
            $firstWord = $lastName !== '' ? explode(' ', $lastName)[0] : 'Unknown';
            $jobTitle = str_replace(' ', '_', trim(preg_replace('/\s+/', ' ', $firstWord)))."'s job post";
        }

        // 2) Build job_position code: job title -> underscores + UuID
        $uid = $rec['uuID'];
        $jobPositionCode = sprintf('%s_%s', $jobTitle, $uid);

        return [
            // Core seeder fields
            'staff_number' => (string) ($rec['EmployeeID'] ?? ''),
            'org_unit' => $rec['Department'] ?? ($rec['Company'] ?? 'Unknown'),
            'job_title' => $jobTitle,
            'job_position' => $jobPositionCode,
            'name' => $rec['LastName'] ?? null,

            // Extras for personAttributes()
            'login' => $rec['LoginID'] ?? null,
            'company_email' => $rec['Email'] ?? null,
            'shift_code' => self::makeShift($rec['ShiftCode'] ?? null),
            'staff_category' => self::makeStaffCategory($rec['Category'] ?? null),
            'job_category' => self::makeJobCategory($rec['JobTitle'] ?? null),
            'gender' => self::normalizeGender($rec['Gender'] ?? null),
            'join_date' => $rec['CompanyStartDate'] ?? null,

            // Manager linkage (used in PASS 2 of StaffSeeder)
            'superior_id' => isset($rec['Manager_reportingTo'])
                ? trim((string) $rec['Manager_reportingTo'])
                : null,
        ];
    }

    /**
     * Normalize gender to 'M' or 'F'.
     * Accepted inputs:
     *  - 'M' / 'F' (any case)
     *  - 0 / '0' -> 'M'
     *  - 1 / '1' -> 'F'
     * Returns null for anything else (e.g., '', null, unexpected strings).
     */
    protected static function normalizeGender($value): ?string
    {
        if (is_null($value)) {
            return null;
        }

        // Convert everything to string for uniformity
        $v = trim((string) $value);
        RoleMapper::updateOrCreate(['role_name' => 'st_gender_F'], [
            'scope' => 'global',
            'enabled' => true,
            'conditions' => 'gender=F',
            'label' => 'Staff gender is female ',
            'category' => 'canonical',
        ]);
        RoleMapper::updateOrCreate(['role_name' => 'st_gender_M'], [
            'scope' => 'global',
            'enabled' => true,
            'conditions' => 'gender=M',
            'label' => 'Staff gender is male ',
            'category' => 'canonical',
        ]);

        switch (true) {
            // Handle numeric values (int or string)
            case $v === '0':
            case $value === 0:
                // Handle letters (case-insensitive)
            case strtoupper($v) === 'M':
                return 'M';

            case $v === '1':
            case $value === 1:

            case strtoupper($v) === 'F':
                return 'F';

            default:
                return null;
        }
    }

    protected static function makeShift($value): ?string
    {
        if (is_null($value) || $value === '') {
            return null;
        }

        // Normalize the input
        $v = trim((string) $value);

        // Split safely on the first hyphen only (handles cases like "A-B-C" by keeping "B-C" as pattern)
        $parts = explode('-', $v, 2);
        $groupRaw = $parts[0] ?? null;
        $patternRaw = $parts[1] ?? null;

        // Normalize to uppercase and trim; null if missing/empty
        $group = isset($groupRaw) && $groupRaw !== '' ? strtoupper(trim($groupRaw)) : null;
        $pattern = isset($patternRaw) && $patternRaw !== '' ? strtoupper(trim($patternRaw)) : null;

        // If no pattern present, we can still return the raw code (e.g., "A"),
        // but we should avoid creating a pattern role for null/empty.
        // Similarly, avoid group role if group is null.
        if (is_null($group) || $group === '') {
            Log::warning('WeaverToStaff::makeShift missing group', ['value' => $v]);
        } else {
            RoleMapper::updateOrCreate(['role_name' => 'st_shift_group_'.$group], [
                'scope' => 'global',
                'enabled' => true,
                'conditions' => 'shift_code='.$group.'-*',
                'label' => 'Shift Group '.$group.' in a particular pattern',
                'category' => 'canonical',
            ]);
        }

        if (is_null($pattern) || $pattern === '') {
            Log::warning('WeaverToStaff::makeShift missing pattern', ['value' => $v]);
        } else {
            RoleMapper::updateOrCreate(['role_name' => 'st_shift_pattern_'.$pattern], [
                'scope' => 'global',
                'enabled' => true,
                'conditions' => 'shift_code=*-'.$pattern,
                'label' => 'Shift of Pattern Type '.$pattern,
                'category' => 'canonical',
            ]);
        }

        // Return the normalized original value (uppercase group-pattern if both exist)
        // If only group exists, return the original $v; otherwise null if it was empty earlier.
        return $v;
    }

    protected static function makeStaffCategory($value): ?string
    {
        if (is_null($value) || $value === '') {
            return null;
        }

        // Convert everything to string for uniformity
        $v = trim((string) $value);
        RoleMapper::updateOrCreate(['role_name' => 'st_category_'.$v], [
            'scope' => 'global',
            'enabled' => true,
            'conditions' => 'staff_category='.$v,
            'label' => 'Staff Category '.$v,
            'category' => 'canonical',
        ]);

        return $v;
    }

    protected static function makeJobCategory($value): ?string
    {
        if (is_null($value) || trim((string) $value) === '') {
            return null;
        }

        // Normalize to string and trim
        $v = trim((string) $value);

        // 1) Convert underscores to spaces early (e.g., "Senior_Officer" -> "Senior Officer")
        $v = str_replace('_', ' ', $v);

        // 2) Truncate at the first dash-like separator: hyphen, en dash, em dash
        //    e.g., "Senior Officer - Forecast & Costing" -> "Senior Officer"
        $v = preg_split('/\s*[\-–—]\s*/u', $v, 2)[0] ?? $v;

        // 3) Remove numbers that are NOT embedded in words.
        //    Keeps "B2B", "Level2", "H2O"; removes " 1 ", "(2)", "- 300" (standalone)
        $v = preg_replace('/(?<!\p{L})\p{N}+(?!\p{L})/u', '', $v);

        // 4) Normalize spacing around punctuation that may remain
        $v = preg_replace('/\s*([–—_,.:;()\/])\s*/u', ' $1 ', $v); // (we already removed '-' via truncation)
        $v = preg_replace('/\s+/u', ' ', $v);
        $v = trim($v);

        // If cleaning makes it empty, skip
        if ($v === '') {
            return null;
        }

        // 5) Title case (Unicode-safe)
        $v = function_exists('mb_convert_case') ? mb_convert_case($v, MB_CASE_TITLE, 'UTF-8') : ucwords(strtolower($v));

        // 6) Persist canonical role mapping
        RoleMapper::updateOrCreate(['role_name' => 'jt_category_'.$v], [
            'scope' => 'global',
            'enabled' => true,
            'conditions' => 'job_category='.$v,
            'label' => 'Job Category '.$v,
            'category' => 'canonical',
        ]);

        return $v;
    }
}

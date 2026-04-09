<?php

declare(strict_types=1);

namespace Bites\Organization\Authorization;

use Bites\Organization\Structure\JobPosition;
use Bites\Organization\Structure\OrgUnit;
use Bites\Employment\Models\Staff;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ObservedBy([RoleMapperObserver::class])]
class RoleMapper extends Model
{
    protected $table = 'role_mappers';

    protected $fillable = [
        'scope',
        'role_name',
        'priority',
        'org_unit_id',
        'job_position_id',
        'conditions',
        'enabled',
        'label',
        'category',
    ];

    // Relationships (adjust namespaces/models to your app structure)
    public function staff()
    {
        return $this->belongsToMany(Staff::class, 'role_staff', 'role_id', 'staff_id')
            ->withPivot(['org_unit_id', 'enabled', 'starts_at', 'ends_at', 'note', 'link_priority'])
            ->withTimestamps();
    }

    public function orgUnit(): BelongsTo
    {
        return $this->belongsTo(OrgUnit::class);
    }

    public function ou()
    {
        return $this->belongsTo(OrgUnit::class, 'org_unit_id');
    }

    public function jobPosition()
    {
        return $this->belongsTo(JobPosition::class, 'job_position_id');
    }

    /* ---------- Query Scopes ---------- */

    /**
     * Scope: enabled rows only.
     */
    #[Scope]
    protected function enabled($query)
    {
        return $query->where('enabled', true);
    }

    /**
     * Scope: by scope ('global' or 'ou').
     */
    #[Scope]
    protected function forScope($query, string $scope)
    {
        return $query->where('scope', $scope);
    }

    /**
     * Scope: OU-aware—prefer specific OU maps but include generic (null) as fallback.
     */
    #[Scope]
    protected function forOrgUnitOrGeneric($query, ?int $orgUnitId)
    {
        return $query->where(function ($q) use ($orgUnitId): void {
            if ($orgUnitId) {
                $q->where('org_unit_id', $orgUnitId);
            }

            $q->orWhereNull('org_unit_id');
        });
    }

    /**
     * Custom creation resolver for ModelJsonSeeder.
     * Allows friendly JSON and resolves FKs/defaults before create().
     */
    public static function resolveCreation(array $record)
    {
        // Example: resolve org_unit by slug/name if provided
        if (! isset($record['org_unit_id'])) {
            $ouName = $record['org_unit_name'] ?? null;
            if ($ouName) {
                $ouQuery = OrgUnit::query();
                if ($ouName) {
                    $ouQuery->where('name', $ouName);
                }

                $orgUnit = $ouQuery->first();
                $record['org_unit_id'] = $orgUnit?->getKey();
            }

            unset($record['org_unit_name']);
        }

        // Resolve job_position_id by code/title if provided
        if (! isset($record['job_position_id'])) {
            $posCode = $record['job_position_code'] ?? null;
            $posTitle = $record['job_position_title'] ?? null;

            if ($posCode || $posTitle) {
                $jpQuery = JobPosition::query();
                if ($posCode) {
                    $jpQuery->where('code', $posCode);
                }

                if ($posTitle) {
                    $jpQuery->orWhere('title', $posTitle);
                }

                $jobPosition = $jpQuery->first();
                $record['job_position_id'] = $jobPosition?->getKey();
            }

            unset($record['job_position_code'], $record['job_position_title']);
        }

        // Normalize types
        $record['enabled'] = (bool) ($record['enabled'] ?? true);
        $record['priority'] = (int) ($record['priority'] ?? 100);

        // Ensure JSON conditions are array or null
        if (isset($record['conditions']) && ! is_array($record['conditions'])) {
            // If a string is provided, try decode; else null
            if (is_string($record['conditions'])) {
                $decoded = json_decode($record['conditions'], true);
                $record['conditions'] = is_array($decoded) ? $decoded : null;
            } else {
                $record['conditions'] = null;
            }
        }

        // Role::firstOrCreate(
        //     ['name' => $record['role_name'], 'team_id' => $record['org_unit_id']],
        //     [
        //         'description'   => $record['label'],
        //     ]
        // );

        // Create the row
        return static::updateOrCreate(['id' => $record['id']], $record);
    }

    protected function casts(): array
    {
        return [
            'conditions' => 'array',
            'enabled' => 'boolean',
            'priority' => 'integer',
        ];
    }
}

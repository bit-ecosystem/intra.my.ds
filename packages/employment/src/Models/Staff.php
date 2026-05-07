<?php

declare(strict_types=1);

namespace Bites\Employment\Models;

use App\Models\PersonAttribute;
use App\Models\User;
use App\Observers\StaffObserver;
use Bites\Organization\Structure\JobPosition;
use Bites\Organization\Structure\OrgUnit;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;
use Bites\Business\Lms\Entities\Certificate;

#[ObservedBy([StaffObserver::class])]
class Staff extends Model
{
    use HasFactory;
    use HasRoles;

    protected $guard_name = 'web';

    protected $table = 'staff';

    protected $fillable = [
        'user_id',
        'staff_number',
        'name',
        // 'join_date',
        // 'end_date',
        'cost_center_id',
        'org_unit_id',
        'job_position_id',
    ];

    /**
     * Relationships
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // public function costCenter(): BelongsTo
    // {
    //     return $this->belongsTo(CostCenter::class);
    // }

    public function orgUnit(): BelongsTo
    {
        return $this->belongsTo(OrgUnit::class, 'org_unit_id');
    }

    public function jobPosition(): BelongsTo
    {
        return $this->belongsTo(JobPosition::class);
    }
    
    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class, 'for_staff');
    }


    public function staffRoleLinks()
    {
        return $this->belongsToMany(Role::class, 'role_staff', 'staff_id', 'role_id')
            ->withPivot(['org_unit_id', 'enabled', 'starts_at', 'ends_at', 'note', 'link_priority'])
            ->withTimestamps();
    }

    // Convenience scope: only active links by date & enabled
    public function activeRoleMappers(?int $orgUnitId = null)
    {
        $today = now()->toDateString();

        $q = $this->roleMappers()
            ->wherePivot('enabled', true)
            ->where(function ($qq) use ($today): void {
                $qq->whereNull('starts_at')->orWhere('starts_at', '<=', $today);
            })
            ->where(function ($qq) use ($today): void {
                $qq->whereNull('ends_at')->orWhere('ends_at', '>=', $today);
            })
            ->where('role_mappers.enabled', true);

        if ($orgUnitId) {
            $q->wherePivot('org_unit_id', $orgUnitId);
        }

        return $q->orderByRaw('COALESCE(role_mapper_staff.link_priority, role_mappers.priority) ASC');
    }

    /**
     * Polymorphic relationship for person attributes
     */
    public function personAttributes()
    {
        return $this->morphMany(PersonAttribute::class, 'attributable');
    }

    protected function staffOldNumber(): Attribute
    {
        return Attribute::make(
            get: function () {
                // Uses eager-loaded collection if present, otherwise queries
                $attr = $this->relationLoaded('personAttributes')
                    ? $this->personAttributes->firstWhere('key', 'login')
                    : $this->personAttributes()->where('key', 'login')->first();

                return $attr->value ?? null;
            },
        );
    }

    protected function shiftCode(): Attribute
    {
        return Attribute::make(get: function () {
            // Retrieves the 'shift_code' PersonAttribute value (if present).
            return $this->personAttributes()
                ->where('key', 'shift_code')
                ->value('value');
        });
    }

    public function getStaffAttributeValue(string $key, $default = null)
    {
        $attr = $this->personAttributes->firstWhere('key', $key);

        return $attr->value ?? $default;
    }

    protected function isPeopleManager(): Attribute
    {
        return Attribute::make(get: function () {
            $position = $this->jobPosition;
            if (! $position) {
                return false;
            }

            // If you used withCount('subordinates'), prefer the count:
            if (isset($position->subordinates_count)) {
                return $position->subordinates_count > 0;
            }

            // Otherwise compute on demand:
            return $position->is_people_manager;
            // uses JobPosition accessor
        });
    }

    protected function casts(): array
    {
        return [
            // 'birth_date' => 'date',
            // 'join_date' => 'date',
            // 'end_date' => 'date',
        ];
    }
}

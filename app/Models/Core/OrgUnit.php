<?php

declare(strict_types=1);

namespace App\Models\Core;

use App\Models\Dms\Document;
use App\Models\Hrm\JobPosition;
use App\Models\Hrm\WorkforcePlan;
use App\Models\RoleMapper;
use App\Models\Workflow\Turtle;
use App\Observers\OrgUnitObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([OrgUnitObserver::class])]
class OrgUnit extends Model
{
    protected $table = 'org_units';

    protected $guarded = [];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function turtles()
    {
        return $this->hasMany(Turtle::class);
    }

    public function roleMappers()
    {
        return $this->hasMany(RoleMapper::class);
    }

    public function roles()
    {
        return $this->hasMany(OrgRole::class);
    }

    public function workforce()
    {
        return $this->hasMany(WorkforcePlan::class, 'org_unit_id');
    }

    public function parent()
    {
        return $this->belongsTo(OrgUnit::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(OrgUnit::class, 'parent_id');
    }

    public function owner()
    {
        return $this->belongsTo(JobPosition::class);
    }

    public function jobPositions()
    {
        return $this->hasMany(JobPosition::class, 'org_unit_id');
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'org_unit_id');
    }
    //  public static function resolveCreation(array $data): self
    // {
    //     $staff_id = \App\Models\Hrm\Staff::where('staff_number', $data['staff_number'])->value('id');
    //     $data['for_staff'] = $staff_id ?? null;
    //     $data['by_staff'] = $staff_id ?? null;

    //     unset($data['staff_number'], $data['user_id']);

    //     return self::create($data);
    // }

}

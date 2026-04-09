<?php

declare(strict_types=1);

namespace Bites\Organization\Structure;

use Bites\Base\Workflow\Turtle;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([OrgRoleObserver::class])]
class OrgRole extends Model
{
    protected $table = 'org_roles';

    protected $guarded = [];

    public function orgUnit()
    {
        return $this->belongsTo(OrgUnit::class, 'org_unit_id');
    }

    public function jobPosition()
    {
        return $this->belongsTo(JobPosition::class);
    }

    public function turtlesAsSupplier()
    {
        return $this->hasMany(Turtle::class, 'supplier_id');
    }

    public function turtlesAsCustomer()
    {
        return $this->hasMany(Turtle::class, 'customer_id');
    }

    public function turtlesAsRole()
    {
        return $this->hasMany(Turtle::class, 'org_role_id');
    }
}

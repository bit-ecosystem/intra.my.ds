<?php

declare(strict_types=1);

namespace Bites\Attachables\Models;

use Bites\Core\Organization\OrgUnit;
use Illuminate\Database\Eloquent\Model;

class ModelAccessControl extends Model
{
    protected $fillable = [
        'org_unit_id',
        'role_group_id',
        'role_type',
    ];

    public function accessible()
    {
        return $this->morphTo();
    }

    public function orgUnit()
    {
        return $this->belongsTo(OrgUnit::class);
    }

    public function roleGroup()
    {
        return $this->belongsTo(RoleGroup::class);
    }
}

<?php

declare(strict_types=1);

namespace Bites\Attachables\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Core\OrgUnit;
use App\Models\User;

class RoleGroup extends Model
{
    protected $fillable = [
        'org_unit_id',
        'code',
        'name',
        'is_global'
    ];
 
    public function orgUnit()
    {
        return $this->belongsTo(OrgUnit::class);
    }
 
    public function users()
    {
        return $this->belongsToMany(User::class,'role_group_users');
    }
 
    public function permissions()
    {
        return $this->hasMany(RoleGroupPermission::class);
    }
}
 
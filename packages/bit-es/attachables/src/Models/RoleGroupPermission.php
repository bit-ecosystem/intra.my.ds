<?php

declare(strict_types=1);

namespace Bites\Attachables\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class RoleGroupPermission extends Model
{
    protected $fillable = [
        'role_group_id',
        'role_type'
    ];
 
    public function roleGroup()
    {
        return $this->belongsTo(RoleGroup::class);
    }
}
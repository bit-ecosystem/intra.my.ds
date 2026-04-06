<?php

declare(strict_types=1);

namespace Bites\Shared\Models;

use Bites\Service\Resources\StakeHolderResource;
use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

#[UseResource(StakeHolderResource::class)]
class StakeHolder extends Model
{
    protected $table = 'model_stake_holders';

    protected $fillable = [
        'role_id',
        'can_view',
        'can_edit',
    ];

    protected $casts = [
        'can_view' => 'boolean',
        'can_edit' => 'boolean',
    ];

    /** Polymorphic owner (Module, Course, etc.) */
    public function assignable()
    {
        return $this->morphTo();
    }

    /** Related Spatie role */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}

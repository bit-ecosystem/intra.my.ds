<?php

declare(strict_types=1);

namespace Bites\Shared\Concerns;

use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Builder;

trait HasStakeHolder
{
    public function stakeHolder()
    {
        return $this->morphMany(
            \Bites\Shared\Models\StakeHolder::class,
            'assignable'
        );
    }

    public function viewRoles()
    {
        return $this->belongsToMany(Role::class, 'role_assignables', 'assignable_id', 'role_id')
            ->where('assignable_type', static::class)
            ->wherePivot('can_view', true);
    }

    public function editRoles()
    {
        return $this->belongsToMany(Role::class, 'role_assignables', 'assignable_id', 'role_id')
            ->where('assignable_type', static::class)
            ->wherePivot('can_edit', true);
    }

    /** ---------- Scopes ---------- */

    public function scopeVisibleTo(Builder $query, $user)
    {
        if ($user->hasRole('super-admin')) {
            return $query;
        }

        return $query->whereHas('stakeHolder', function ($q) use ($user) {
            $q->where('can_view', true)
              ->whereIn('role_id', $user->roles->pluck('id'));
        });
    }
}

<?php

declare(strict_types=1);

namespace Bites\Service\Concerns;

use App\Models\User;
use Bites\Service\Models\StakeHolder;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Models\Role;

trait HasStakeHolder
{
    public function stakeHolder()
    {
        return $this->morphMany(
            StakeHolder::class,
            'assignable'
        );
    }

    public function canViewBy(User $user): bool
    {
        if ($user->hasRole('super-admin')) {
            return true;
        }

        return $this->stakeHolder()
            ->where('can_view', true)
            ->whereIn('role_id', $user->roles->pluck('id'))
            ->exists();
    }

    public function canEditBy(User $user): bool
    {
        if ($user->hasRole('super-admin')) {
            return true;
        }

        return $this->stakeHolder()
            ->where('can_edit', true)
            ->whereIn('role_id', $user->roles->pluck('id'))
            ->exists();
    }

    public function viewRoles()
    {
        return $this->belongsToMany(Role::class, 'model_stake_holders', 'assignable_id', 'role_id')
            ->where('assignable_type', static::class)
            ->wherePivot('can_view', true);
    }

    public function editRoles()
    {
        return $this->belongsToMany(Role::class, 'model_stake_holders', 'assignable_id', 'role_id')
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

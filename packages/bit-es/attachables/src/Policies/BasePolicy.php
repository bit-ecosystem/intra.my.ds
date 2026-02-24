<?php

declare(strict_types=1);

namespace Bites\Policies;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

abstract class BasePolicy
{
    protected function check(User $user, Model $model, string $roleType): bool
    {
        return $model->accessControls()
            ->where('role_type', $roleType)
            ->where(function ($q) use ($user) {
                $q->whereIn(
                    'org_unit_id',
                    $user->accessibleOrgUnitIds()
                )
                    ->orWhereNull('org_unit_id');
            })
            ->whereIn(
                'role_group_id',
                $user->roleGroupIds()
            )
            ->exists();
    }
    public function view(User $user, Model $model)
    {
        return $this->check($user, $model, 'view');
    }

    public function update(User $user, Model $model)
    {
        return $this->check($user, $model, 'update');
    }

    public function delete(User $user, Model $model)
    {
        return $this->check($user, $model, 'delete');
    }
}

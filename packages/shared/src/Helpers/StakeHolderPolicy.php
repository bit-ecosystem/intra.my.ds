<?php

declare(strict_types=1);

namespace Bites\Shared\Helpers;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\User;

abstract class StakeHolderPolicy
{
    use HandlesAuthorization;

    public function view(User $user, $model): bool
    {
        return method_exists($model, 'canViewBy')
            && $model->canViewBy($user);
    }

    public function update(User $user, $model): bool
    {
        return method_exists($model, 'canEditBy')
            && $model->canEditBy($user);
    }
}
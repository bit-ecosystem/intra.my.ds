<?php

declare(strict_types=1);

namespace Bites\Service\Helpers;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

abstract class StakeHolderPolicy
{
    use HandlesAuthorization;

    public function view(User $user, $model): bool
    {
        // return method_exists($model, 'canViewBy')
        //     && $model->canViewBy($user);
        return true;
    }

    public function update(User $user, $model): bool
    {
        return false;
        // return method_exists($model, 'canEditBy')
        //     && $model->canEditBy($user);
    }
}

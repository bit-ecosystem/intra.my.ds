<?php

declare(strict_types=1);

namespace Bites\Platform\Auth\Services;

use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Hash;

class LocalAuthService
{
    public function userExists(string $login): bool
    {
        $isEmail = filter_var($login, FILTER_VALIDATE_EMAIL);

        return $isEmail
            ? User::where('email', $login)->exists()
            : User::where('name', $login)->exists();
    }

    public function authenticate(string $login, string $password, bool $remember): bool
    {
        $isEmail = filter_var($login, FILTER_VALIDATE_EMAIL);

        $user = $isEmail
            ? User::where('email', $login)->first()
            : User::where('name', $login)->first();

        if (! $user) {
            return false;
        }

        if (! Hash::check($password, $user->password)) {
            return false;
        }

        Filament::auth()->login($user, $remember);

        return true;
    }
}

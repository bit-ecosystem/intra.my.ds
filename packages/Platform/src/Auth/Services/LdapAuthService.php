<?php

declare(strict_types=1);

namespace Bites\Platform\Auth\Services;

use Filament\Facades\Filament;
use LdapRecord\Models\ActiveDirectory\User as AdUser;
use LdapRecord\Models\Model;

class LdapAuthService
{
    public function userExists(string $login): bool
    {
        $isEmail = filter_var($login, FILTER_VALIDATE_EMAIL);

        $user = $isEmail
            ? AdUser::where('mail', $login)->first()
            : AdUser::where('samaccountname', $login)->first();

        return $user instanceof Model;
    }

    public function authenticate(string $login, string $password, bool $remember): bool
    {
        $isEmail = filter_var($login, FILTER_VALIDATE_EMAIL);

        $credentials = [
            $isEmail ? 'mail' : 'samaccountname' => $login ?? null,
            'password' => $password ?? null,
        ];

        return Filament::auth()->attempt($credentials, $remember);
    }
}

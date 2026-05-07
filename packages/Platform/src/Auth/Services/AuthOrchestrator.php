<?php

declare(strict_types=1);

namespace Bites\Platform\Auth\Services;

class AuthOrchestrator
{
    public function __construct(
        protected LdapAuthService $ldap,
        protected LocalAuthService $local,
    ) {}

    public function authenticate(string $login, string $password, bool $remember): string
    {
        // STEP 1: Check LDAP first
        if ($this->ldap->userExists($login)) {
            // dd($this->ldap->userExists($login));
            // dd($this->ldap->authenticate($login, $password, $remember));
            // LDAP user → must pass LDAP auth
            return $this->ldap->authenticate($login, $password, $remember)
                ? 'ldap_success'
                : 'ldap_failed';
        }

        // STEP 2: LDAP not found → fallback local
        if ($this->local->authenticate($login, $password, $remember)) {
            return 'local_success';
        }

        // STEP 3: Not found anywhere
        return 'not_found';
    }
}

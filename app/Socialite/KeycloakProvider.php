<?php

declare(strict_types=1);

namespace App\Socialite;

use GuzzleHttp\ClientInterface;
use SocialiteProviders\Keycloak\Provider as BaseKeycloakProvider;

class KeycloakProvider extends BaseKeycloakProvider
{
    protected function getHttpClient(): ClientInterface
    {
        return new \GuzzleHttp\Client([
            'verify' => config('services.keycloak.ca_cert') ?: false,
        ]);
    }
}

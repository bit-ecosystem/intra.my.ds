<?php

return [
    'issuer' => env('IDP_ISSUER', env('APP_URL')),

    // Default token lifetimes
    'access_token_minutes' => env('IDP_ACCESS_TOKEN_MINUTES', 60),
    'refresh_token_days' => env('IDP_REFRESH_TOKEN_DAYS', 30),

    // Default scopes you'll expose to clients
    'scopes' => [
        'view-profile' => 'Read basic profile info',
        'manage-account' => 'Manage account settings',
    ],

    // Optional: allow automatic client creation by admin
    'auto_create_client' => true,

    // Filament resource config
    'filament' => [
        'oauth_client_resource' => true,
    ],
];

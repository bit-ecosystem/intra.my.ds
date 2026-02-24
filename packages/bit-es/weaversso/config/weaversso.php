<?php

return [
    'oidc' => [
        'issuer' => env('OIDC_ISSUER', 'https://intra.my.ds.amkor.com'),
        'jwks_uri' => env('OIDC_JWKS_URI', env('OIDC_ISSUER', 'https://intra.my.ds.amkor.com').'/.well-known/jwks.json'),
    ],
    'weaver' => [
        'base_url' => env('WEAVER_BASE_URL', 'http://10.40.2.131'),
        'sso_mode' => env('WEAVER_SSO_MODE', 'endpoint'), // 'endpoint' or 'header'
        'sso_shared_key' => env('WEAVER_SSO_SHARED_KEY'),       // used for HMAC sign
        // NOTE: no need for session_cookie here        // NOTE: no need for session_cookie here; Ecology sets it itself
    ],
    'weaver_landing_path' => '/wui/index.html',
];

# WeaverSSO (bit-es/weaversso)

A Laravel package providing a clean SSO bridge from `intra.my` (Laravel Passport v13 OIDC/PKCE) into `weaver.dev` (Weaver E‑cology), using Weaver's non‑intrusive SSO guide.

## Install (monorepo)

Add a path repository in your root `composer.json`:

```json
{
    "repositories": [{ "type": "path", "url": "packages/bit-es/weaversso" }],
    "require": {
        "bit-es/weaversso": "*"
    }
}
```
Then:
```
composer update
```
The package uses Laravel auto-discovery.
Publish config & migrations

```
php artisan vendor:publish --tag=weaversso-config
php artisan vendor:publish --tag=weaversso-mphp artisan vendor:publish --tag=weaversso-migrations
```

Configure environment
```
OIDC_ISSUER=https://intra.my
OIDC_JWKS_URI=https://intra.my/.well-known/jwks.json

WEAVER_BASE_URL=https://weaver.dev
WEAVER_SSO_MODE=endpoint
WEAVER_SSO_SHARED_KEY=change-me    # if Weaver requires HMAC/sign
WEAVER_SESSION_COOKIE=ecology-session  # replace with the real cookie name
```

Map users
Insert rows into weaver_accounts (user_id -> weaver_login) or rely on email local-part fallback.
Use

Users authenticate on intra.my (Passport v13 OIDC/PKCE).
Redirect them to: /sso/weaver/login.
The package creates a Weaver session via weaver.dev SSO, sets the cookie, and redirects to weaver.dev/portal/home.do.

Notes

Replace the endpoint /sso/nonintrusive/login and cookie name with actual values from the Weaver SSO doc.
Use HTTPS end-to-end.



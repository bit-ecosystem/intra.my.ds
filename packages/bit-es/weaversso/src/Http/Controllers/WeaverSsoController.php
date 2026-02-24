<?php

declare(strict_types=1);

namespace Bites\WeaverSSO\Http\Controllers;

use Bites\WeaverSSO\Contracts\WeaverSsoClientInterface;
use Bites\WeaverSSO\Models\WeaverAccount;
use Bites\WeaverSSO\Services\OidcTokenVerifier;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cookie;

class WeaverSsoController extends Controller
{
    public function login(Request $request, WeaverSsoClientInterface $weaverSsoClient, OidcTokenVerifier $oidcTokenVerifier)
    {
        // Option A: rely on Laravel session (ensure auth middleware on package routes)
        $user = $request->user();
        if (! $user) {
            return redirect()->route('login');
        }

        // Option B: verify posted id_token (uncomment if used)
        // if ($request->filled('id_token')) {
        //     $claims = $verifier->verifyIdToken($request->string('id_token'));
        //     // find/sync local user by $claims['email'] etc.
        // }

        // 1) Resolve Weaver login
        // dd($user);
        $weaverLogin = $this->resolveWeaverLogin($user);
        if (! $weaverLogin) {
            abort(403, 'No Weaver account mapping for this user.');
        }

        // 2) Issue Weaver session
        [$cookieName, $cookieValue] = $weaverSsoClient->issueSession($weaverLogin);

        // 3) Set cookie on weaver.dev
        $weaverHost = parse_url(config('weaversso.weaver.base_url'), PHP_URL_HOST);
        Cookie::queue(
            Cookie::make(
                $cookieName,
                $cookieValue,
                120,  // minutes
                '/',
                $weaverHost,
                true, // secure
                true, // httpOnly
                false,
                'Lax' // or 'Strict' if same-site
            )
        );

        // 4) Redirect to Weaver landing

        $landingInit = rtrim(config('weaversso.weaver.base_url'), '/')
            .'/weaver/sso/init'
            .'?email='.urlencode($weaverLogin)
            .'&ts='.time()
            .'&sign='.hash_hmac('sha256', $weaverLogin.'.'.time(), config('weaversso.weaver.sso_shared_key'));

        return redirect()->away($landingInit);
    }

    public function logout()
    {
        $cookieName = config('weaversso.weaver.session_cookie');
        $weaverHost = parse_url(config('weaversso.weaver.base_url'), PHP_URL_HOST);
        Cookie::queue(Cookie::forget($cookieName, '/', $weaverHost));

        // If Weaver has a logout endpoint, redirect there (optional)
        return redirect()->away(rtrim(config('weaversso.weaver.base_url'), '/').'/logout.do');
    }

    protected function resolveWeaverLogin($user): ?string
    {
        // Prefer explicit mapping table
        $map = WeaverAccount::where('user_id', $user->id)->value('weaver_login');
        if ($map) {
            return $map;
        }

        // Fallback: derive from email local part (adjust to your org)
        if (! empty($user->email)) {
            return strtok($user->email, '@') ?: null;
        }

        return null;
    }
}

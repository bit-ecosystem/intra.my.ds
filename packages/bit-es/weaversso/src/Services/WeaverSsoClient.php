<?php

declare(strict_types=1);

namespace Bites\WeaverSSO\Services;

use Bites\WeaverSSO\Contracts\WeaverSsoClientInterface;
use Illuminate\Support\Facades\Http;

class WeaverSsoClient implements WeaverSsoClientInterface
{
    protected string $baseUrl;

    protected string $mode;

    protected ?string $sharedKey;

    protected string $sessionCookie;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('weaversso.weaver.base_url'), '/');
        $this->mode = config('weaversso.weaver.sso_mode', 'endpoint');
        $this->sharedKey = config('weaversso.weaver.sso_shared_key');
        $this->sessionCookie = config('weaversso.weaver.session_cookie', 'ecology-session');
    }

    public function issueSession(string $weaverLogin): array
    {
        if ($this->mode === 'endpoint') {
            // Replace '/sso/nonintrusive/login' with the real path from Weaver’s SSO doc.
            $resp = Http::asForm()->post($this->baseUrl.'/sso/nonintrusive/login', [
                'account' => $weaverLogin,
                'sign' => $this->signature($weaverLogin),
                'ts' => time(),
            ]);

            if (! $resp->ok()) {
                throw new \RuntimeException('Weaver SSO endpoint error: HTTP '.$resp->status());
            }

            $session = $resp->json('session');
            if (! $session) {
                throw new \RuntimeException('Weaver session not returned');
            }

            return [$this->sessionCookie, $session];
        }

        if ($this->mode === 'header') {
            // If using reverse proxy: transform header → session cookie (infra responsibility).
            $resp = Http::withHeaders([
                'X-Weaver-Login' => $weaverLogin,
            ])->post($this->baseUrl.'/_sso/initialize');

            if (! $resp->ok()) {
                throw new \RuntimeException('Weaver SSO proxy error: HTTP '.$resp->status());
            }

            $session = $resp->json('session');
            if (! $session) {
                throw new \RuntimeException('Weaver session not returned');
            }

            return [$this->sessionCookie, $session];
        }

        throw new \RuntimeException('Unknown Weaver SSO mode: '.$this->mode);
    }

    protected function signature(string $login): ?string
    {
        if (! $this->sharedKey) {
            return null;
        }

        return hash_hmac('sha256', $login.'.'.time(), $this->sharedKey);
    }
}

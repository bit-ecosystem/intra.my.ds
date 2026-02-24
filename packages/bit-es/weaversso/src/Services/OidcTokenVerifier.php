<?php

declare(strict_types=1);

namespace Bites\WeaverSSO\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Http;

class OidcTokenVerifier
{
    protected string $issuer;

    protected string $jwksUri;

    protected array $jwks;

    public function __construct()
    {
        $this->issuer = config('weaversso.oidc.issuer');
        $this->jwksUri = config('weaversso.oidc.jwks_uri');
        $this->jwks = $this->fetchJwks();
    }

    public function verifyIdToken(string $jwt): array
    {
        $parts = explode('.', $jwt);
        if (count($parts) < 2) {
            throw new \RuntimeException('Invalid JWT');
        }

        $header = json_decode($this->urlsafeB64Decode($parts[0]), true);
        $kid = $header['kid'] ?? null;
        if (! $kid) {
            throw new \RuntimeException('Missing kid in JWT header');
        }

        $jwk = collect($this->jwks['keys'] ?? [])->firstWhere('kid', $kid);
        if (! $jwk) {
            throw new \RuntimeException('JWK not found for kid');
        }

        $publicKeyPem = $this->jwkToPem($jwk);
        $payload = (array) JWT::decode($jwt, new Key($publicKeyPem, $header['alg'] ?? 'RS256'));

        if (($payload['iss'] ?? null) !== $this->issuer) {
            throw new \RuntimeException('Issuer mismatch');
        }

        if (isset($payload['exp']) && $payload['exp'] < time()) {
            throw new \RuntimeException('Token expired');
        }

        return $payload;
    }

    protected function fetchJwks(): array
    {
        $resp = Http::get($this->jwksUri);
        if (! $resp->ok()) {
            throw new \RuntimeException('Unable to fetch JWKS');
        }

        return $resp->json();
    }

    protected function urlsafeB64Decode(string $input): string
    {
        $pad = strlen($input) % 4;
        if ($pad !== 0) {
            $input .= str_repeat('=', 4 - $pad);
        }

        return base64_decode(strtr($input, '-_', '+/'));
    }

    // Minimal RSA JWK → PEM
    protected function jwkToPem(array $jwk): string
    {
        $n = $this->urlsafeB64Decode($jwk['n']);
        $e = $this->urlsafeB64Decode($jwk['e']);

        $mod = "\x02".$this->encodeLen(strlen($n)).$n;
        $exp = "\x02".$this->encodeLen(strlen($e)).$e;
        $rsakey = "\x30".$this->encodeLen(strlen($mod.$exp)).$mod.$exp;

        $algoOid = "\x30\x0D\x06\x09\x2A\x86\x48\x86\xF7\x0D\x01\x01\x01\x05\x00";
        $bitString = "\x03".$this->encodeLen(strlen($rsakey) + 1)."\x00".$rsakey;
        $spki = "\x30".$this->encodeLen(strlen($algoOid.$bitString)).$algoOid.$bitString;

        return "-----BEGIN PUBLIC KEY-----\n".
               chunk_split(base64_encode($spki), 64, "\n").
               "-----END PUBLIC KEY-----\n";
    }

    protected function encodeLen(int $length): string
    {
        if ($length <= 0x7F) {
            return chr($length);
        }

        $temp = ltrim(pack('N', $length), "\x00");

        return chr(0x80 | strlen($temp)).$temp;
    }
}

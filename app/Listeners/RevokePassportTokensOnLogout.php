<?php

declare(strict_types=1);

namespace App\Listeners;

use Filament\Facades\Filament;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\DB;

class RevokePassportTokensOnLogout
{
    public function handle(Logout $logout): void
    {
        $user = $logout->user;
        // dd(session()->all());
        // Only act for the Filament auth guard (avoid logging out other guards unless intended)
        // If you want global logout on any guard, remove this guard check.
        $filamentGuard = Filament::getAuthGuard();
        if ($logout->guard !== $filamentGuard) {
            return;
        }

        if (! $user) {
            return;
        }

        session()->invalidate();
        // session()->regenerateToken();
        // Revoke all non-revoked access tokens for this user
        $tokens = DB::table('oauth_access_tokens')
            ->where('user_id', $user->getAuthIdentifier())
            ->where('revoked', false)
            ->get();

        foreach ($tokens as $token) {
            DB::table('oauth_access_tokens')
                ->where('id', $token->id)
                ->update(['revoked' => true]);

            DB::table('oauth_refresh_tokens')
                ->where('access_token_id', $token->id)
                ->update(['revoked' => true]);
        }

        // If you prefer Eloquent with HasApiTokens:
        // $user->tokens()->where('revoked', false)->get()->each(function ($token) {
        //     $token->revoke();
        //     DB::table('oauth_refresh_tokens')
        //       ->where('access_token_id', $token->id)
        //       ->update(['revoked' => true]);
        // });
    }
}

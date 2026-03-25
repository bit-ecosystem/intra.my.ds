<?php

use App\Http\Controllers\ReceiveDataController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::post('/receive', [ReceiveDataController::class, 'store']);

Route::post('/slo/revoke', function (Request $request) {
    // Auth this endpoint with client credentials or a service account guard
    // e.g., middleware('client') or 'auth:api' with a special machine user.
    // Validate inputs:
    $request->validate([
        'user_id' => ['required', 'integer'],
        'client_id' => ['nullable', 'integer'],
        'all' => ['nullable', 'boolean'], // if true, revoke all user tokens
    ]);

    $userId = (int) $request->input('user_id');
    $clientId = $request->input('client_id');
    $revokeAll = (bool) $request->boolean('all', true);

    // Fetch tokens for the user
    $builder = DB::table('oauth_access_tokens')->where('user_id', $userId)->where('revoked', false);

    if (! $revokeAll && $clientId) {
        $builder->where('client_id', $clientId);
    }

    $tokens = $builder->get();

    foreach ($tokens as $token) {
        // Revoke access token
        DB::table('oauth_access_tokens')->where('id', $token->id)->update(['revoked' => true]);

        // Revoke associated refresh tokens
        DB::table('oauth_refresh_tokens')->where('access_token_id', $token->id)->update(['revoked' => true]);
    }

    // Optional: if the user has a web session on App A and you want to ensure front-channel logout,
    // you could enqueue an action to invalidate it on next request or trigger user-specific session invalidation
    // (Laravel sessions are cookie-bound; server-to-server cannot directly kill a browser's session cookie).

    return response()->json(['revoked' => $tokens->count()]);
});

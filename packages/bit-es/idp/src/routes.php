<?php

use Bites\Idp\Http\Controllers\UserInfoController;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function (): void {
    // OAuth authorize/token routes are registered by Passport::routes()
    // Optional: UI override for consent pages can be provided
});

Route::prefix('api')->middleware('auth:api')->group(function (): void {
    Route::get('userinfo', [UserInfoController::class, 'show'])->name('idp.userinfo');
});

// Optional OIDC discovery endpoint
Route::get('.well-known/openid-configuration', function () {
    return response()->json([
        'issuer' => config('bites-idp.issuer'),
        'userinfo_endpoint' => url('/api/userinfo'),
        'authorization_endpoint' => url('/oauth/authorize'),
        'token_endpoint' => url('/oauth/token'),
    ]);
});

Route::get('/login', function () {
    return redirect()->to(Filament::getPanel('staff')->getLoginUrl());
})->name('login');

Route::get('/', function () {
    return redirect()->to(Filament::getPanel('staff')->getLoginUrl());
});

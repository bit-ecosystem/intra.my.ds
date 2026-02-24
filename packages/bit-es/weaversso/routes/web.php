<?php

use Bites\WeaverSSO\Http\Controllers\WeaverSsoController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->group(function (): void {
    Route::get('/sso/weaver/login', [WeaverSsoController::class, 'login'])->name('weaversso.login');
    Route::post('/sso/weaver/logout', [WeaverSsoController::class, 'logout'])->name('weaversso.logout');
});

Route::get('/test/weaver/debug', function (): void {
    $user = Auth::user();   // login as any user
    // dd([
    //     'user' => $user,
    //     'weaver_login' => strtok($user->email, '@'),
    //     'redirect_url' => route('weaversso.login'),
    // ]);
})->middleware(['web', 'auth'])->name('weaversso.test.debug');

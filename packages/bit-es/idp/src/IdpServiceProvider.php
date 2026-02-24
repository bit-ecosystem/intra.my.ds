<?php

declare(strict_types=1);

namespace Bites\Idp;

use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class IdpServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // Register view namespace first
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'bites');

        // If you load keys from a custom directory
        Passport::loadKeysFrom('/etc/laravel/passport');

        // Register the consent view (namespaced)
        // Passport::authorizationView('bites::oauth.authorize');

        $this->commands([
            \Laravel\Passport\Console\ClientCommand::class,
        ]);

        Passport::authorizationView(function ($parameters): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View {
            // dd($parameters);
            return view('bites::oauth.authorize', $parameters);
            // return view('filament.idp.pages.o-auth-authorize', $parameters);
        });
        Passport::tokensCan([
            'user:read' => 'Retrieve the user info',
        ]);
        Passport::defaultScopes([
            'user:read',
        ]);
    }

    public function register(): void
    {
        // Publish config, load routes, etc.
        $this->publishes([__DIR__.'/config/bit-es-idp.php' => config_path('bit-es-idp.php')], 'config');

        $this->loadRoutesFrom(__DIR__.'/routes.php');

        Event::listen(Logout::class, [\App\Listeners\RevokePassportTokensOnLogout::class, 'handle']);
        // Default token TTLs, can be overridden by config
        // Passport::tokensExpireIn(now()->addMinutes(config('bit-es-idp-passport.access_token_minutes', 60)));
        // Passport::refreshTokensExpireIn(now()->addDays(config('bit-es-idp-passport.refresh_token_days', 30)));
        Passport::tokensCan([
            'profile' => 'Read user profile',
            'email' => 'Read user email address',
            'roles' => 'Read user roles',
        ]);
        Passport::DefaultScopes(['profile']);
    }
}

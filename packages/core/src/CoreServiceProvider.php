<?php

namespace Bites\Core;

use App\Listeners\RevokePassportTokensOnLogout;
use BladeUI\Icons\Factory as IconFactory;
use Illuminate\Auth\Events\Logout;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Console\ClientCommand;
use Laravel\Passport\Passport;

class CoreServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'corp-login');

        $this->callAfterResolving(IconFactory::class, function (IconFactory $factory): void {
            $factory->add('bites', [
                'path' => __DIR__.'/../resources/svg',
                'prefix' => 'bites',
            ]);
        });
        // If you load keys from a custom directory
        Passport::loadKeysFrom('/etc/laravel/passport');

        // Register the consent view (namespaced)
        // Passport::authorizationView('bites::oauth.authorize');

        $this->commands([
            ClientCommand::class,
        ]);

        Passport::authorizationView(function ($parameters): Factory|View {
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
        $this->publishes([__DIR__.'/config/bites-idp.php' => config_path('bites-idp.php')], 'config');

        $this->loadRoutesFrom(__DIR__.'/routes.php');

        Event::listen(Logout::class, [RevokePassportTokensOnLogout::class, 'handle']);
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

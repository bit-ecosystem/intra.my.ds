<?php

declare(strict_types=1);

namespace App\Providers;

use App\Filament\Staff\Pages\Help;
use App\Listeners\SyncKeycloakAttributes;
use App\Listeners\SyncLdap;
use Bites\Shared\Models\HelpPage;
use App\Socialite\KeycloakProvider;
use BladeUI\Icons\Factory;
use DutchCodingCompany\FilamentSocialite\Events\Login;
use Filament\Facades\Filament;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use LdapRecord\Laravel\Events\Import\Synchronized;
use SocialiteProviders\Manager\SocialiteWasCalled;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Event::listen(SocialiteWasCalled::class, function (SocialiteWasCalled $socialiteWasCalled): void {
        //     $socialiteWasCalled->extendSocialite('keycloak', KeycloakProvider::class);
        // });

        // Event::listen(Login::class, [SyncKeycloakAttributes::class, 'handle']);
        Event::listen(Synchronized::class, [
            SyncLdap::class,
            'handle',
        ]);
    }

    public function boot(): void
    {
        // FilamentView::registerRenderHook(
        //     PanelsRenderHook::USER_MENU_AFTER,
        //     fn (): string => Blade::render('<livewire:language-switcher />'),
        // );
        // Blade Icons registry
        $this->callAfterResolving(Factory::class, function (Factory $factory): void {
            $factory->add('myicons', [
                'path' => base_path('/resources/svg'),
                'prefix' => 'myicon',
            ]);
        });

        /**
         * Help page routing (Filament v4-safe)
         * Mount under the STAFF panel's path/prefix.
         * Produces route name: filament.staff.pages.help
         * and path: [panel_path]/help/{slug}  (panel_path can be empty → /help/{slug})
         */
        app()->booted(function (): void {
            $panel = Filament::getPanel('staff');
            if (! $panel) {
                return; // Panel not registered yet; avoid errors
            }

            Route::group([
                'as' => sprintf('filament.%s.', $panel->getId()),
                'prefix' => $panel->getPath(), // typically '' for Staff → /help/{slug}
            ], function (): void {
                Route::get(Help::routePattern(), Help::class)->name('pages.help');
            });
        });
        /**
         * Render hook: show help icon only when a HelpPage entry exists
         * Tries exact (panel + resource + page), then wildcard page ('*') fallback.
         */

        // FilamentView::registerRenderHook(
        //     PanelsRenderHook::PAGE_HEADER_ACTIONS_AFTER,

        // );

        //     PanelsRenderHook::PAGE_HEADER_ACTIONS_AFTER,
        //     function () {
        //         $route = request()->route();
        //         $ruri = request()->getRequestUri();
        //         // dd(request()->getRequestUri());
        //         $action = $route?->getAction() ?? [];
        //         $defaults = $route?->defaults ?? [];

        //         // Render a debug box with all useful keys
        //         return '<div style="padding:8px; background:#fee2e2; color:#7f1d1d; border:1px solid #fecaca; border-radius:6px; max-width:800px; font-size:12px;">'
        //             . '<div><strong>DEBUG ROUTE</strong></div>'
        //             . '<div><strong>name:</strong> ' . e($route?->getName()) . '</div>'
        //             . '<div><strong>uri:</strong> ' . e($ruri) . '</div>'
        //             . '<div><strong>defaults:</strong><pre style="white-space:pre-wrap;">' . e(json_encode($defaults, JSON_PRETTY_PRINT)) . '</pre></div>'
        //             . '<div><strong>action:</strong><pre style="white-space:pre-wrap;">' . e(json_encode(array_keys($action), JSON_PRETTY_PRINT)) . '</pre></div>'
        //             . '<div><strong>action (raw):</strong><pre style="white-space:pre-wrap;">' . e(json_encode($action, JSON_PRETTY_PRINT)) . '</pre></div>'
        //             . '</div>';
        //     }
        // );
    }
}

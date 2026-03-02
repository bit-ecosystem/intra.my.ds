<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use DutchCodingCompany\FilamentSocialite\FilamentSocialitePlugin;
use DutchCodingCompany\FilamentSocialite\Provider;
use Filament\Auth\MultiFactor\App\AppAuthentication;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationItem;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentIcon;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsIconAlias;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\View\View;

class StaffPanelProvider extends PanelProvider
{
    protected static ?string $title = 'Finance dashboard';

    public function panel(Panel $panel): Panel
    {
        return $panel
            // Panel identity & routing
            ->id('staff')
            ->path('staff')
            ->homeUrl(fn(): string => route(config('bites.staff_panel.route', '/')))
            // Authentication pages
            // ->login(\Bites\CorpLogin\Pages\Login::class) // Use package-provided login page
            ->login(\App\Filament\Pages\Auth\Login::class)
            ->registration(\App\Filament\Pages\Auth\Register::class)
            ->profile(\Bites\CorpLogin\Pages\Profile::class)
            ->passwordReset(\Bites\CorpLogin\Pages\ResetPassword::class)
            ->multiFactorAuthentication([AppAuthentication::make()], isRequired: true)

            // Branding
            // ->brandName(__('bites::components/pagination.label'))
            ->brandName('Staff Panel')
            
            ->colors([
                'primary' => '#0F4B8F',
            ])
            ->navigationItems([
                NavigationItem::make('Document')
                    ->url('https://intra.my.ds.amkor.com/dms/documents')
                    ->icon('myicon-book-open-02')
                    ->sort(41)
                    ->group('Knowledge'),
                NavigationItem::make('Learn')
                    ->url('https://intra.my.ds.amkor.com/lms/courses')
                    ->icon('myicon-course')
                    ->sort(42)
                    ->group('Knowledge'),
            ])
            ->discoverResources(in: app_path('Filament/Staff/Resources'), for: 'App\Filament\Staff\Resources')
            ->resources([])
            ->discoverPages(in: app_path('Filament/Staff/Pages'), for: 'App\Filament\Staff\Pages')
            ->pages([])
            ->discoverWidgets(in: app_path('Filament/Staff/Widgets'), for: 'App\Filament\Staff\Widgets')
            ->widgets([])

            // Socialite Plugin for Keycloak
            // ->plugin(
            //     FilamentSocialitePlugin::make()
            //         ->slug('staff')
            //         ->providers([
            //             Provider::make('keycloak')
            //                 ->label('AD Account Login')
            //                 ->icon('myicon-l-keycloak')
            //                 ->color(Color::Gray)
            //                 ->outlined(false)
            //                 ->stateless(true),
            //         ])
            //         ->registration(true)
            // )

            // Middleware stack
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
                \App\Http\Middleware\SetLocale::class,
            ])

            // Custom render hooks for UI
            ->renderHook('panels::auth.login.form.after', fn(): View => view('corp-login::panel.extra'))
            ->renderHook('panels::auth.register.form.after', fn(): View => view('corp-login::panel.extra'));
    }

    public function boot(Panel $panel): void
    {
        // Register custom UI hooks
        FilamentView::registerRenderHook(
            PanelsRenderHook::USER_MENU_BEFORE,
            fn(): View => view('corp-login::panel.icon-links-umb'),
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::GLOBAL_SEARCH_BEFORE,
            fn(): View => view('corp-login::panel.icon-links-gsb'),
        );

        // Register custom icons
        FilamentIcon::register([
            PanelsIconAlias::PAGES_DASHBOARD_NAVIGATION_ITEM => 'myicon-dashboard',
        ]);
    }
}

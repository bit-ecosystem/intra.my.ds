<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\Register;
use App\Http\Middleware\SetLocale;
use Bites\Organization\Identity\Pages\Login;
use Bites\Organization\Identity\Pages\Profile;
use Bites\Organization\Identity\Pages\ResetPassword;
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
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\View\View;

class StaffPanelProvider extends PanelProvider
{
    protected static ?string $title = 'Finance dashboard';

    public function panel(Panel $panel): Panel
    {
        return $panel
                ->sidebarWidth('15rem')
            // Panel identity & routing
            ->id('staff')
            ->path('staff')
            ->homeUrl(fn (): string => route(config('bites.staff_panel.route', '/')))
            // Authentication pages
            // ->login(\Bites\CorpLogin\Pages\Login::class) // Use package-provided login page
            // ->login(Login::class)
            ->login(\Bites\Platform\Branding\Pages\Login::class) // Use custom login page with branding
            ->registration(Register::class)
            ->profile(Profile::class)
            ->passwordReset(ResetPassword::class)
            ->multiFactorAuthentication([AppAuthentication::make()], isRequired: true)

            // Branding
            // ->brandName(__('bites::components/pagination.label'))
            ->brandName('ATM Staff Intranet')

            ->colors([
                'primary' => '#0F4B8F',
            ])
            ->navigationItems([
                NavigationItem::make()
                    ->label(fn (): string|array|null => __('Document'))
                    ->url('https://intra.my.ds.amkor.com/lms/attachments')
                    ->icon('myicon-book-open-02')
                    ->sort(41)
                    ->group(fn (): string|array|null => __('Knowledge')),
                NavigationItem::make()
                    ->label(fn (): string|array|null => __('Learn'))
                    ->url('https://intra.my.ds.amkor.com/lms/courses')
                    ->icon('myicon-course')
                    ->sort(42)
                    ->group(fn (): string|array|null => __('Knowledge')),
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
                SetLocale::class,
            ])

            // Custom render hooks for UI
            ->renderHook('panels::auth.login.form.after', fn (): View => view('corp-login::panel.extra'))
            ->renderHook('panels::auth.register.form.after', fn (): View => view('corp-login::panel.extra'));
    }

    public function boot(Panel $panel): void
    {
        // Register custom UI hooks
        FilamentView::registerRenderHook(
            PanelsRenderHook::USER_MENU_AFTER,
            fn (): View => view('corp-login::panel.icon-links-umb'),
        );
        FilamentView::registerRenderHook(
            PanelsRenderHook::USER_MENU_AFTER,
            fn (): string => Blade::render('<livewire:language-switcher />'),
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::GLOBAL_SEARCH_BEFORE,
            fn (): View => view('corp-login::panel.icon-links-gsb'),
        );

        // Register custom icons
        FilamentIcon::register([
            PanelsIconAlias::PAGES_DASHBOARD_NAVIGATION_ITEM => 'myicon-dashboard',
        ]);
    }
}

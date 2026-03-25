<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use App\Http\Middleware\Authenticate;
use App\Http\Middleware\RedirectToCoreLogin;
use Bites\FilamentBlueprints\Resources\Blueprints\BlueprintResource;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Assets\Css;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class CorePanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('core')
            ->path('core')
            ->brandName('Administration')
            ->homeUrl(fn (): string => route(config('bites.staff_panel.route', '/')))
            ->colors([
                'primary' => Color::Rose,
            ])
            ->assets([
                Css::make('theme-css', resource_path('css/enterprise/theme.css')),
            ])
            ->discoverResources(in: app_path('Filament/Core/Resources'), for: 'App\Filament\Core\Resources')
            ->resources([
                BlueprintResource::class,
            ])
            ->discoverPages(in: app_path('Filament/Core/Pages'), for: 'App\Filament\Core\Pages')
            ->pages([
                // Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Core/Widgets'), for: 'App\Filament\Core\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
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
                Authenticate::class,
            ])
            ->authMiddleware([
                RedirectToCoreLogin::class,

            ]);
    }
}

<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use App\Http\Middleware\RedirectToCoreLogin;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class LmsPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('lms')
            ->path('lms')
            ->brandName('Digital Campus')
            ->homeUrl(fn (): string => route(config('bites.staff_panel.route', '/')))
            ->colors([
                'primary' => '#09829F',
            ])
            ->discoverResources(in: app_path('Filament/Lms/Resources'), for: 'App\Filament\Lms\Resources')
            ->discoverPages(in: app_path('Filament/Lms/Pages'), for: 'App\Filament\Lms\Pages')
            ->pages([
                // Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Lms/Widgets'), for: 'App\Filament\Lms\Widgets')
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
            ])
            ->authMiddleware([
                RedirectToCoreLogin::class,
            ]);
    }
}

<?php

declare(strict_types=1);

namespace Bites\Providers\Filament;

use App\Http\Middleware\RedirectToCoreLogin;
use Filament\Actions\Action;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\View\PanelsRenderHook;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class ErpPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('erp')
            ->path('erp')
            ->brandName('ERP Panel')
            ->homeUrl(fn (): string => route(config('bites.staff_panel.route', '/')))
            ->colors([
                'primary' => '#7F174B',
            ])
            ->discoverResources(in: app_path('Filament/Erp/Resources'), for: 'Bites\Filament\Erp\Resources')
            ->discoverPages(in: app_path('Filament/Erp/Pages'), for: 'Bites\Filament\Erp\Pages')
            ->pages([
                // Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Erp/Widgets'), for: 'Bites\Filament\Erp\Widgets')
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

            // ->renderHook(
            //     PanelsRenderHook::SIDEBAR_NAV_START,
            //     fn(): string => Action::make('go')
            //         ->label('Go somewhere')
            //         ->url('/')
            //         ->render(), // returns HTML string

            // )

            ->authMiddleware([
                RedirectToCoreLogin::class,
            ]);
    }
}

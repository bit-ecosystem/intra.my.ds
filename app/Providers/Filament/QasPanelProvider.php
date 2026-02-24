<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class QasPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('qas')
            ->path('qas')
            ->brandName('Quality Assurance')
            ->homeUrl(fn (): string => route(config('bites.staff_panel.route', '/')))
            ->colors([
                'primary' => Color::Rose,
            ])
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Quality Events')
                    ->icon(Heroicon::OutlinedBellAlert)
                    ->collapsed(true),
                NavigationGroup::make()
                    ->label('Quality Control')
                    ->icon(Heroicon::OutlinedHandThumbDown)
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('Product Control')
                    ->icon(Heroicon::OutlinedCube)
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('Improvement Actions')
                    ->icon(Heroicon::OutlinedClipboardDocument)
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('Compliance')
                    ->icon(Heroicon::OutlinedShieldCheck)
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('Quality Planning')
                    ->icon('myicon-p-erp')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('Supplier Quality')
                    ->icon('myicon-supplier')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('Quality Tools')
                    ->icon('myicon-toolbox')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('Analytics')
                    ->icon(Heroicon::OutlinedChartPie)
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('Master Data')
                    ->icon(Heroicon::ArrowsPointingIn)
                    ->collapsed(),
            ])
            ->plugins([])
            ->discoverResources(in: app_path('Filament/Qas/Resources'), for: 'App\Filament\Qas\Resources')
            ->discoverPages(in: app_path('Filament/Qas/Pages'), for: 'App\Filament\Qas\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Qas/Widgets'), for: 'App\Filament\Qas\Widgets')
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
                \App\Http\Middleware\RedirectToCoreLogin::class,
            ]);
    }
}

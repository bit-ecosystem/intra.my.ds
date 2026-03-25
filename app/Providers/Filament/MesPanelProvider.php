<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use App\Http\Middleware\RedirectToCoreLogin;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class MesPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('mes')
            ->path('mes')
            ->brandName('MES')
            ->homeUrl(fn (): string => route(config('bites.staff_panel.route', '/')))
            ->colors([
                'primary' => Color::Fuchsia,
            ])
            ->discoverResources(in: app_path('Filament/Mes/Resources'), for: 'App\Filament\Mes\Resources')
            ->discoverPages(in: app_path('Filament/Mes/Pages'), for: 'App\Filament\Mes\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Mes/Widgets'), for: 'App\Filament\Mes\Widgets')
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
            ])

            ->navigationGroups([
                NavigationGroup::make()->label('Test')->items([
                    NavigationItem::make('Google')->url('https://google.com')->icon('heroicon-o-link'),
                ]),
            ]);

        // ->navigationGroups([
        //     NavigationGroup::make()
        //         ->label('Production')
        //         ->items([
        //             NavigationItem::make('Production Orders')->url('/mes/production-orders')->icon('heroicon-o-clipboard-document'),
        //             NavigationItem::make('Production Schedule')->url('/mes/production-schedule')->icon('heroicon-o-calendar'),
        //             NavigationItem::make('Performance')->url('/mes/production-performance')->icon('heroicon-o-chart-bar'),
        //             NavigationItem::make('Capability')->url('/mes/production-capability')->icon('heroicon-o-cog'),
        //         ]),
        //     NavigationGroup::make()
        //         ->label('Quality')
        //         ->items([
        //             NavigationItem::make('Specifications')->url('/mes/quality-specs')->icon('heroicon-o-document-text'),
        //             NavigationItem::make('Test Results')->url('/mes/quality-tests')->icon('heroicon-o-check-circle'),
        //             NavigationItem::make('Nonconformance')->url('/mes/nonconformance')->icon('heroicon-o-exclamation-circle'),
        //         ]),
        //     NavigationGroup::make()
        //         ->label('Inventory')
        //         ->items([
        //             NavigationItem::make('Material Inventory')->url('/mes/material-inventory')->icon('heroicon-o-cube'),
        //             NavigationItem::make('Consumption')->url('/mes/material-consumption')->icon('heroicon-o-arrow-down-circle'),
        //             NavigationItem::make('Movement')->url('/mes/material-movement')->icon('heroicon-o-arrow-right-circle'),
        //         ]),
        //     NavigationGroup::make()
        //         ->label('Maintenance')
        //         ->items([
        //             NavigationItem::make('Requests')->url('/mes/maintenance-requests')->icon('heroicon-o-wrench'),
        //             NavigationItem::make('Records')->url('/mes/maintenance-records')->icon('heroicon-o-archive-box'),
        //             NavigationItem::make('Equipment Health')->url('/mes/equipment-health')->icon('heroicon-o-heart'),
        //         ]),
        //     NavigationGroup::make()
        //         ->label('Resources')
        //         ->items([
        //             NavigationItem::make('Personnel')->url('/mes/personnel')->icon('heroicon-o-user-group'),
        //             NavigationItem::make('Skills')->url('/mes/skills')->icon('heroicon-o-academic-cap'),
        //             NavigationItem::make('Tools')->url('/mes/tools')->icon('heroicon-o-wrench-screwdriver'),
        //         ]),
        //     NavigationGroup::make()
        //         ->label('Process')
        //         ->items([
        //             NavigationItem::make('Recipes & BOMs')->url('/mes/recipes')->icon('heroicon-o-book-open'),
        //             NavigationItem::make('Routing')->url('/mes/routing')->icon('heroicon-o-map'),
        //             NavigationItem::make('Parameters')->url('/mes/process-parameters')->icon('heroicon-o-adjustments-horizontal'),
        //         ]),
        //     NavigationGroup::make()
        //         ->label('KPIs & Dashboards')
        //         ->items([
        //             NavigationItem::make('Production KPIs')->url('/mes/kpis/production')->icon('heroicon-o-chart-pie'),
        //             NavigationItem::make('Quality Metrics')->url('/mes/kpis/quality')->icon('heroicon-o-chart-bar'),
        //             NavigationItem::make('Inventory KPIs')->url('/mes/kpis/inventory')->icon('heroicon-o-chart-line'),
        //         ]),
        //     NavigationGroup::make()
        //         ->label('Reports')
        //         ->items([
        //             NavigationItem::make('Daily Production')->url('/mes/reports/daily-production')->icon('heroicon-o-document-report'),
        //             NavigationItem::make('Quality Compliance')->url('/mes/reports/quality-compliance')->icon('heroicon-o-shield-check'),
        //             NavigationItem::make('Inventory Movement')->url('/mes/reports/inventory-movement')->icon('heroicon-o-arrow-path'),
        //         ]),
        // ]);
    }
}

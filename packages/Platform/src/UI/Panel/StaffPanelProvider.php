<?php

declare(strict_types=1);

namespace Bites\Platform\UI\Panel;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class StaffPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $panel
            ->default()
            ->login(\Bites\Platform\Branding\Pages\Login::class)
            ->id(config('bites.panels.staff.0', 'staff'))
            ->path(config('bites.panels.staff.1', 'staff'))
            ->colors(['primary' => config('bites.panels.staff.2', Color::Blue)])
            ->brandName(config('bites.panels.staff.3', 'ATM Staff Intranet'))
            ->homeUrl(fn(): string => route(config('bites.panels.staff.4', '/')));

        $packages = config('bites.packages', []);

        foreach ($packages as $package => $namespace) {
            $panel
                ->discoverResources(
                    in: base_path(sprintf('vendor/bit-es/%s/Http/UI/Staff/Resources', $package)),
                    for: $namespace . '\Http\UI\Staff\Resources',
                )
                ->discoverPages(
                    in: base_path(sprintf('vendor/bit-es/%s/Http/UI/Staff/Pages', $package)),
                    for: $namespace . '\Http\UI\Staff\Pages',
                )
                ->discoverWidgets(
                    in: base_path(sprintf('vendor/bit-es/%s/Http/UI/Staff/Widgets', $package)),
                    for: $namespace . '\Http\UI\Staff\Widgets',
                );
        }

        return $panel
            ->navigationGroups([
                'To Do',
                'Artifact',
                'Catalog',
                'Knowledge',
                'Location',
                'Emergency',
            ])
            ->pages([
                // Dashboard::class,
            ])
            ->widgets([
                // AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}

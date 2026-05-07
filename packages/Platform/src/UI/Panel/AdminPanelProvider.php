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

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        // dd(config('bites.queue'));
        $panel
            ->default()
            ->login(\Bites\Platform\Branding\Pages\LoginLdap::class)
            ->id(config('bites.ui.panels.admin.0', 'admin'))
            ->path(config('bites.ui.panels.admin.1', 'admin'))
            ->colors(['primary' => config('bites.ui.panels.admin.2', Color::Amber)])
            ->brandName(config('bites.ui.panels.admin.3', 'Administration'))
            ->homeUrl(fn (): string => route(config('bites.ui.panels.admin.4', 'filament.admin.pages.dashboard')));

        $packages = config('bites.ui.packages', []);
        foreach ($packages as $package => $namespace) {
            $panel
                ->discoverResources(
                    in: base_path(sprintf('vendor/bit-es/%s/Http/UI/Admin/Resources', $package)),
                    for: $namespace.'\Http\UI\Admin\Resources',
                )
                ->discoverPages(
                    in: base_path(sprintf('vendor/bit-es/%s/Http/UI/Admin/Pages', $package)),
                    for: $namespace.'\Http\UI\Admin\Pages',
                )
                ->discoverWidgets(
                    in: base_path(sprintf('vendor/bit-es/%s/Http/UI/Admin/Widgets', $package)),
                    for: $namespace.'\Http\UI\Admin\Widgets',
                );
        }

        return $panel
            ->navigationGroups([
                'Classroom',
                'Report Card',
                'Library',
                'Shop',
            ])
            ->pages([
                Dashboard::class,
            ])
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

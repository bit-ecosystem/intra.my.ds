<?php

declare(strict_types=1);

namespace Bites\Organization\Providers;

use App\Http\Middleware\RedirectToCoreLogin;
use Bites\Organization\Identity\Resources\AuthCodes\AuthCodeResource;
use Bites\Organization\Identity\Resources\Clients\ClientResource;
use Bites\Organization\Identity\Resources\DeviceCodes\DeviceCodeResource;
use Bites\Organization\Identity\Resources\RefreshTokens\RefreshTokenResource;
use Bites\Organization\Identity\Resources\Tokens\TokenResource;
use Bites\Organization\Identity\Resources\Users\UserResource;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
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

class IdpPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('idp')
            ->path('idp')
            ->brandName('Identity Provider')
            // ->login()
            ->colors([
                'primary' => Color::Rose,
            ])
            ->discoverResources(in: app_path('Filament/Idp/Resources'), for: 'App\Filament\Idp\Resources')
            ->discoverResources(
                in: base_path('vendor/bit-es/core/src/Identity/Resources'),
                for: 'Bites\Organization\Identity\Resources'
            )
            ->discoverPages(in: app_path('Filament/Idp/Pages'), for: 'App\Filament\Idp\Pages')
            ->discoverWidgets(in: app_path('Filament/Idp/Widgets'), for: 'App\Filament\Idp\Widgets')
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
                // Authenticate::class,
                RedirectToCoreLogin::class,
            ]);
    }
}

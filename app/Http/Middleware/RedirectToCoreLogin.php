<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RedirectToCoreLogin
{
    public function handle($request, Closure $next)
    {
        $panelId = Filament::getCurrentPanel()->getId();
        Log::info('RedirectToCoreLogin middleware invoked by panel: '.$panelId);
        if (! Auth::check()) {
            return redirect()->guest(route('filament.staff.auth.login'));
        }

        // If MFA secret is missing, redirect to MFA setup page
        if (empty(Auth::user()->app_authentication_secret)) {
            return redirect()->route('filament.staff.auth.multi-factor-authentication.set-up-required');
        }

        // If user has bio needing update, redirect to biodata page
        if (! Auth::user()->bio_readonly) {
            return redirect()->to(filament()->getPanel('staff')->getUrl());
        }

        if (! Auth::user()->can('go_'.$panelId.'_panel')) {

            Notification::make()
                ->title(__('You do not have access to '.$panelId.' panel.'))
                ->body(__('If you believe this is a mistake, please contact the administrator. Redirecting you back to Staff Panel.'))
                ->danger()
                ->seconds(3) // optional
                ->send();

            return redirect()->route('filament.staff.pages.dashboard');
        }

        if (session()->has('locale')) {
            App::setLocale(session()->get('locale'));
        }

        return $next($request);
    }
}

<?php

declare(strict_types=1);

namespace Bites\Platform\Branding\Pages;

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Route;
use Bites\Platform\Auth\Services\AuthOrchestrator;

class Login extends BaseLogin
{
    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('login')
                ->label('Username or Email')
                ->required()
                ->autocomplete('username')
                ->autofocus(),

            $this->getPasswordFormComponent(),
            $this->getRememberFormComponent(),
        ]);
    }

    public function authenticate(): ?LoginResponse
    {
        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $e) {
            $this->getRateLimitedNotification($e)?->send();
            return null;
        }

        $data = $this->form->getState();

        $result = app(AuthOrchestrator::class)->authenticate(
            $data['login'],
            $data['password'],
            $data['remember'] ?? false
        );

        return match ($result) {

            'ldap_success',
            'local_success' => $this->success(),

            'ldap_failed' => $this->fail('Incorrect Active Directory credentials.'),

            'not_found' => $this->redirectToRegister(),

            default => null,
        };
    }

    protected function success(): LoginResponse
    {
        session()->regenerate();

        return app(LoginResponse::class);
    }

    protected function fail(string $message): null
    {
        Notification::make()
            ->title($message)
            ->danger()
            ->send();

        return null;
    }

    protected function redirectToRegister(): null
    {
        $panelId = filament()->getCurrentPanel()->getId();
        $route = "filament.{$panelId}.auth.register";

        session()->flash(
            'auth.notice',
            'We couldn’t find your account. You may request access.'
        );

        $this->redirect(
            Route::has($route) ? route($route) : '/register'
        );

        return null;
    }
}
<?php

declare(strict_types=1);

namespace Bites\Organization\Identity\Pages;

use App\Models\User as AppUser;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Models\Contracts\FilamentUser;
use Filament\Notifications\Notification;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use LdapRecord\Models\ActiveDirectory\User as AdUser;
use LdapRecord\Models\Model;

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

    /**
     * Build credentials for LDAP attempt using LDAP attribute keys:
     * - Email input -> 'mail'
     * - Username input -> 'samaccountname'
     */
    protected function getCredentialsFromFormData(array $data): array
    {
        $isEmail = filter_var($data['login'] ?? '', FILTER_VALIDATE_EMAIL);

        return [
            $isEmail ? 'mail' : 'samaccountname' => $data['login'] ?? null,
            'password' => $data['password'] ?? null,
        ];
    }

    /**
     * Pre-check LDAP; if absent, try local Eloquent fallback before redirecting.
     */
    public function authenticate(): ?LoginResponse
    {
        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $tooManyRequestsException) {
            $this->getRateLimitedNotification($tooManyRequestsException)?->send();

            return null; // allowed by Filament
        }

        $data = $this->form->getState();
        $loginVal = $data['login'] ?? '';
        $isEmail = filter_var($loginVal, FILTER_VALIDATE_EMAIL);
        $remember = $data['remember'] ?? false;

        // 1) LDAP existence check (by mail or samaccountname)
        $ldapUser = $isEmail
            ? AdUser::where('mail', $loginVal)->first()
            : AdUser::where('samaccountname', $loginVal)->first();

        if (! $ldapUser instanceof Model) {
            // 2) LDAP user not found -> local fallback (Eloquent)
            $local = $isEmail
                ? AppUser::where('email', $loginVal)->first()
                : AppUser::where('name', $loginVal)->first();

            if ($local) {
                // If local user has a password and it matches -> login locally
                if (! empty($local->password) && Hash::check($data['password'] ?? '', $local->password)) {
                    Filament::auth()->login($local, $remember);

                    // Optional: respect Filament's panel gate
                    if (
                        $local instanceof FilamentUser
                        && ! $local->canAccessPanel(Filament::getCurrentPanel())
                    ) {
                        Filament::auth()->logout();
                        $this->throwFailureValidationException();
                    }

                    session()->regenerate();

                    return app(LoginResponse::class);
                }

                // Wrong local password -> show error, stay on login
                Notification::make()
                    ->title('The password is incorrect.')
                    ->danger()
                    ->send();

                return null;
            }

            // 3) Neither LDAP nor local -> redirect to your Register page
            $panelId = Filament::getCurrentPanel()->getId(); // e.g. 'admin'
            $routeName = sprintf('filament.%s.auth.register', $panelId);

            session()->flash('auth.notice', 'We couldn’t find your account in Active Directory. You may request access.');

            if (Route::has($routeName)) {
                $this->redirect(route($routeName));
            } else {
                $this->redirect('/register'); // fallback URL if you prefer
            }

            return null;
        }

        // 4) LDAP user exists -> attempt LDAP auth with mapped keys
        if (! Filament::auth()->attempt($this->getCredentialsFromFormData($data), $remember)) {
            Notification::make()
                ->title('Incorrect AD credentials.')
                ->warning()
                ->send();
            $this->throwFailureValidationException(); // Filament-native message
        }

        // 5) Optional: panel access gate
        $user = Filament::auth()->user();
        if (
            $user instanceof FilamentUser
            && ! $user->canAccessPanel(Filament::getCurrentPanel())
        ) {
            Filament::auth()->logout();
            $this->throwFailureValidationException();
        }

        session()->regenerate();

        return app(LoginResponse::class);
    }
}

<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\User;
use Closure;
use Filament\Actions\Action;
use Filament\Actions\Contracts\HasActions;
use Filament\Auth\MultiFactor\App\AppAuthentication;
use Filament\Forms\Components\OneTimeCodeInput;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Hash;

class VerifyTotp
{
    public static function make(AppAuthentication $appAuthentication): Action
    {
        return Action::make('regenerateAppAuthenticationRecoveryCodes')
            ->label('Forgot Password')
            ->color('gray')
            ->icon(Heroicon::ArrowPath)
            ->link()
            ->modalWidth(Width::Large)
            ->modalIcon(Heroicon::OutlinedArrowPath)
            ->modalIconColor('primary')
            ->modalHeading(__('Resetting Password'))
            ->modalDescription('Please state your email address and verify your TOTP code to proceed.')
            ->schema([
                TextInput::make('email')
                    ->label('Your Email Address'),
                OneTimeCodeInput::make('code')
                    ->label(__('filament-panels::auth/multi-factor/app/actions/regenerate-recovery-codes.modal.form.code.label'))
                    ->validationAttribute(__('filament-panels::auth/multi-factor/app/actions/regenerate-recovery-codes.modal.form.code.validation_attribute'))
                    ->requiredWithout('password')
                    ->rule(function () use ($appAuthentication): Closure {
                        return function (string $attribute, $value, Closure $fail) use ($appAuthentication): void {
                            dump($value);
                            if ($appAuthentication->verifyCode($value)) {
                                return;
                            }

                            $fail(__('filament-panels::auth/multi-factor/app/actions/regenerate-recovery-codes.modal.form.code.messages.invalid'));
                        };
                    }),

            ])
            ->modalSubmitAction(fn (Action $action): Action => $action
                ->label('Reset')
                ->color('danger'))
            ->action(function (Action $action, HasActions $hasActions, array $data): void {
                $email = $data['email'] ?? null;

                if (! $email) {
                    Notification::make()
                        ->title('Email is required')
                        ->danger()
                        ->send();

                    return;
                }

                /** @var User|null $user */
                $user = User::where('email', $email)->first();

                if (! $user) {
                    Notification::make()
                        ->title('User not found')
                        ->danger()
                        ->send();

                    return;
                }

                // You can now use $user->id or any other user data
                Notification::make()
                    ->title('User ID: '.$user->id)
                    ->success()
                    ->send();
                $hasActions->mountAction('setNewPassword', arguments: ['userId' => $user->id]);
                // You can redirect, log, or trigger another action here
            })

            ->registerModalActions([
                Action::make('setNewPassword')
                    ->modalHeading('Set New Password')
                    ->schema([
                        TextInput::make('new_password')
                            ->label('New Password')
                            ->password()
                            ->required(),

                        TextInput::make('confirm_password')
                            ->label('Confirm Password')
                            ->password()
                            ->required()
                            ->same('new_password'),
                    ])
                    ->action(function (Action $action, HasActions $hasActions, array $data): void {
                        $userId = $action->getArguments()['userId'] ?? null;

                        if (! $userId) {
                            Notification::make()
                                ->title('User ID missing')
                                ->danger()
                                ->send();

                            return;
                        }

                        $user = User::find($userId);

                        if (! $user) {
                            Notification::make()
                                ->title('User not found')
                                ->danger()
                                ->send();

                            return;
                        }

                        $user->password = Hash::make($data['new_password']);
                        $user->save();

                        Notification::make()
                            ->title('Password updated successfully')
                            ->success()
                            ->send();
                    }),
            ])
            ->rateLimit(5);
    }
}

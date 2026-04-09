<?php

declare(strict_types=1);

namespace Bites\Organization\Identity\Resources\Users\Pages;

use Bites\Organization\Identity\Resources\Users\UserResource;
use Filament\Actions;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // DeleteAction::make(),

            Actions\Action::make('reset_mfa')
                ->label('Reset MFA Secret')
                ->color('danger')
                ->requiresConfirmation()
                ->icon('heroicon-o-shield-exclamation')
                ->action(function (): void {
                    $this->record->app_authentication_secret = null;
                    $this->record->save();

                    // $this->notify('success', 'MFA secret has been reset.');
                }),
        ];
    }
}

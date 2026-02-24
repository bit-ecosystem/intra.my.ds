<?php

declare(strict_types=1);

namespace Bites\Idp\Resources\Clients\Pages;

use Bites\Idp\Resources\Clients\ClientResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Artisan;

class ListClients extends ListRecords
{
    protected static string $resource = ClientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
            Action::make('createPkce')
                ->schema([
                    TextInput::make('name')
                        ->label('Name')
                        ->helperText('Name of application to use this authentication'),
                    TextInput::make('redirect_uri')
                        ->label('Redirect to?')
                        ->helperText('After authentication, redirect user to'),
                ])
                ->label('Create PKCE Client')
                ->action(function (array $data): void {
                    Artisan::call('passport:client', [
                        '--public' => true,
                        '--name' => $data['name'],
                        '--redirect_uri' => $data['redirect_uri'],
                        '--no-interaction' => true,
                    ]);
                    // Artisan::call('model:list');
                    //     '--help' => true,

                    // ]);
                }),
        ];
    }
}

<?php

declare(strict_types=1);

namespace Bites\Organization\Identity\Resources\Clients\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ClientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('owner_type'),
                TextInput::make('owner_id')
                    ->numeric(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('secret'),
                TextInput::make('provider'),
                Textarea::make('redirect_uris')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('grant_types')
                    ->required()
                    ->columnSpanFull(),
                Toggle::make('revoked')
                    ->required(),
            ]);
    }
}

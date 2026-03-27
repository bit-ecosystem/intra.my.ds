<?php

declare(strict_types=1);

namespace Bites\Core\Identity\Resources\AuthCodes\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AuthCodeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                Select::make('client_id')
                    ->relationship('client', 'name')
                    ->required(),
                Textarea::make('scopes')
                    ->columnSpanFull(),
                Toggle::make('revoked')
                    ->required(),
                DateTimePicker::make('expires_at'),
            ]);
    }
}

<?php

declare(strict_types=1);

namespace Bites\Core\Identity\Resources\DeviceCodes\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class DeviceCodeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->numeric(),
                TextInput::make('client_id')
                    ->required(),
                TextInput::make('user_code')
                    ->required(),
                Textarea::make('scopes')
                    ->required()
                    ->columnSpanFull(),
                Toggle::make('revoked')
                    ->required(),
                DateTimePicker::make('user_approved_at'),
                DateTimePicker::make('last_polled_at'),
                DateTimePicker::make('expires_at'),
            ]);
    }
}

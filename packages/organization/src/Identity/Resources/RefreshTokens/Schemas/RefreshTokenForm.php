<?php

declare(strict_types=1);

namespace Bites\Organization\Identity\Resources\RefreshTokens\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class RefreshTokenForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('access_token_id')
                    ->relationship('accessToken', 'name')
                    ->required(),
                Toggle::make('revoked')
                    ->required(),
                DateTimePicker::make('expires_at'),
            ]);
    }
}

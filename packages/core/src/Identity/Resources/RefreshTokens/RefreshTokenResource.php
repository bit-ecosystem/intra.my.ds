<?php

declare(strict_types=1);

namespace Bites\Core\Identity\Resources\RefreshTokens;

use BackedEnum;
use Bites\Core\Identity\Resources\RefreshTokens\Pages\CreateRefreshToken;
use Bites\Core\Identity\Resources\RefreshTokens\Pages\EditRefreshToken;
use Bites\Core\Identity\Resources\RefreshTokens\Pages\ListRefreshTokens;
use Bites\Core\Identity\Resources\RefreshTokens\Schemas\RefreshTokenForm;
use Bites\Core\Identity\Resources\RefreshTokens\Tables\RefreshTokensTable;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Laravel\Passport\RefreshToken;
use UnitEnum;

class RefreshTokenResource extends Resource
{
    protected static ?string $model = RefreshToken::class;

    protected static string|BackedEnum|null $navigationIcon = 'myicon-refresh-token';

    protected static string|UnitEnum|null $navigationGroup = 'Tokens';

    protected static ?int $navigationSort = 2;

    // protected static ?string $modelLabel = 'Refresh Token';

    public static function form(Schema $schema): Schema
    {
        return RefreshTokenForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RefreshTokensTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRefreshTokens::route('/'),
            'create' => CreateRefreshToken::route('/create'),
            'edit' => EditRefreshToken::route('/{record}/edit'),
        ];
    }
}

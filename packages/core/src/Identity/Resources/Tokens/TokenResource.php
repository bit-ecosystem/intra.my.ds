<?php

declare(strict_types=1);

namespace Bites\Core\Identity\Resources\Tokens;

use BackedEnum;
use Bites\Core\Identity\Resources\Tokens\Pages\CreateToken;
use Bites\Core\Identity\Resources\Tokens\Pages\EditToken;
use Bites\Core\Identity\Resources\Tokens\Pages\ListTokens;
use Bites\Core\Identity\Resources\Tokens\Schemas\TokenForm;
use Bites\Core\Identity\Resources\Tokens\Tables\TokensTable;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Laravel\Passport\Token;
use UnitEnum;

class TokenResource extends Resource
{
    protected static ?string $model = Token::class;

    protected static string|BackedEnum|null $navigationIcon = 'myicon-tokens';

    protected static string|UnitEnum|null $navigationGroup = 'Tokens';

    protected static ?int $navigationSort = 1;

    // protected static ?string $modelLabel = 'Token';

    public static function form(Schema $schema): Schema
    {
        return TokenForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TokensTable::configure($table);
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
            'index' => ListTokens::route('/'),
            'create' => CreateToken::route('/create'),
            'edit' => EditToken::route('/{record}/edit'),
        ];
    }
}

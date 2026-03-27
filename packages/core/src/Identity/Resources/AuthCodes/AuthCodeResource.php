<?php

declare(strict_types=1);

namespace Bites\Core\Identity\Resources\AuthCodes;

use BackedEnum;
use Bites\Core\Identity\Resources\AuthCodes\Pages\CreateAuthCode;
use Bites\Core\Identity\Resources\AuthCodes\Pages\EditAuthCode;
use Bites\Core\Identity\Resources\AuthCodes\Pages\ListAuthCodes;
use Bites\Core\Identity\Resources\AuthCodes\Schemas\AuthCodeForm;
use Bites\Core\Identity\Resources\AuthCodes\Tables\AuthCodesTable;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Laravel\Passport\AuthCode;
use UnitEnum;

class AuthCodeResource extends Resource
{
    protected static ?string $model = AuthCode::class;

    protected static string|BackedEnum|null $navigationIcon = 'myicon-user-code';

    protected static string|UnitEnum|null $navigationGroup = 'Codes';

    // protected static ?string $modelLabel = 'User Codes';

    public static function form(Schema $schema): Schema
    {
        return AuthCodeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AuthCodesTable::configure($table);
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
            'index' => ListAuthCodes::route('/'),
            'create' => CreateAuthCode::route('/create'),
            'edit' => EditAuthCode::route('/{record}/edit'),
        ];
    }
}

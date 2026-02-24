<?php

declare(strict_types=1);

namespace Bites\Idp\Resources\AuthCodes;

use BackedEnum;
use Bites\Idp\Resources\AuthCodes\Pages\CreateAuthCode;
use Bites\Idp\Resources\AuthCodes\Pages\EditAuthCode;
use Bites\Idp\Resources\AuthCodes\Pages\ListAuthCodes;
use Bites\Idp\Resources\AuthCodes\Schemas\AuthCodeForm;
use Bites\Idp\Resources\AuthCodes\Tables\AuthCodesTable;
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

<?php

declare(strict_types=1);

namespace Bites\Idp\Resources\Users;

use App\Models\User;
use BackedEnum;
use Bites\Idp\Resources\Users\Pages\CreateUser;
use Bites\Idp\Resources\Users\Pages\EditUser;
use Bites\Idp\Resources\Users\Pages\ListUsers;
use Bites\Idp\Resources\Users\Schemas\UserForm;
use Bites\Idp\Resources\Users\Tables\UsersTable;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return UserForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UsersTable::configure($table);
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
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}

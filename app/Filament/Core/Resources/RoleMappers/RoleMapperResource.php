<?php

declare(strict_types=1);

namespace App\Filament\Core\Resources\RoleMappers;

use App\Filament\Core\Resources\RoleMappers\Pages\CreateRoleMapper;
use App\Filament\Core\Resources\RoleMappers\Pages\EditRoleMapper;
use App\Filament\Core\Resources\RoleMappers\Pages\ListRoleMappers;
use App\Filament\Core\Resources\RoleMappers\Schemas\RoleMapperForm;
use App\Filament\Core\Resources\RoleMappers\Tables\RoleMappersTable;
use BackedEnum;
use Bites\Organization\Authorization\RoleMapper;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class RoleMapperResource extends Resource
{
    protected static ?string $model = RoleMapper::class;

    protected static string|UnitEnum|null $navigationGroup = 'Authorization';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'role_name';

    public static function form(Schema $schema): Schema
    {
        return RoleMapperForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RoleMappersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\StaffRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRoleMappers::route('/'),
            'create' => CreateRoleMapper::route('/create'),
            'edit' => EditRoleMapper::route('/{record}/edit'),
        ];
    }
}

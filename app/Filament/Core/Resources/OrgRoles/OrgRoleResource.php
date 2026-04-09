<?php

declare(strict_types=1);

namespace App\Filament\Core\Resources\OrgRoles;

use App\Filament\Core\Resources\OrgRoles\Pages\CreateOrgRole;
use App\Filament\Core\Resources\OrgRoles\Pages\EditOrgRole;
use App\Filament\Core\Resources\OrgRoles\Pages\ListOrgRoles;
use App\Filament\Core\Resources\OrgRoles\Schemas\OrgRoleForm;
use App\Filament\Core\Resources\OrgRoles\Tables\OrgRolesTable;
use BackedEnum;
use Bites\Organization\Structure\OrgRole;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class OrgRoleResource extends Resource
{
    protected static ?string $model = OrgRole::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return OrgRoleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OrgRolesTable::configure($table);
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
            'index' => ListOrgRoles::route('/'),
            'create' => CreateOrgRole::route('/create'),
            'edit' => EditOrgRole::route('/{record}/edit'),
        ];
    }
}

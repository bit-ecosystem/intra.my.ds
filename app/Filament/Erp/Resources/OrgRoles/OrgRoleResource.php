<?php

declare(strict_types=1);

namespace App\Filament\Erp\Resources\OrgRoles;

use App\Filament\Erp\Resources\OrgRoles\Pages\CreateOrgRole;
use App\Filament\Erp\Resources\OrgRoles\Pages\EditOrgRole;
use App\Filament\Erp\Resources\OrgRoles\Pages\ListOrgRoles;
use App\Filament\Erp\Resources\OrgRoles\Schemas\OrgRoleForm;
use App\Filament\Erp\Resources\OrgRoles\Tables\OrgRolesTable;
// use Bites\Organization\Structure\OrgRole;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Spatie\Permission\Models\Role;
use UnitEnum;

class OrgRoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static string|BackedEnum|null $navigationIcon = 'myicon-role';

    protected static string|UnitEnum|null $navigationGroup = 'Workforce Management';

    protected static ?string $modelLabel = 'Team Roles';

    protected static ?int $navigationSort = 1;

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

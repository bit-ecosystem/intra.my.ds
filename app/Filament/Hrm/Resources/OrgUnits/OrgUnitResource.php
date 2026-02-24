<?php

declare(strict_types=1);

namespace App\Filament\Hrm\Resources\OrgUnits;

use App\Filament\Hrm\Resources\OrgUnits\Pages\CreateOrgUnit;
use App\Filament\Hrm\Resources\OrgUnits\Pages\EditOrgUnit;
use App\Filament\Hrm\Resources\OrgUnits\Pages\ListOrgUnits;
use App\Filament\Hrm\Resources\OrgUnits\Schemas\OrgUnitForm;
use App\Filament\Hrm\Resources\OrgUnits\Tables\OrgUnitsTable;
use App\Models\Core\OrgUnit;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class OrgUnitResource extends Resource
{
    protected static ?string $model = OrgUnit::class;

    protected static string|UnitEnum|null $navigationGroup = 'Organizational Management';

    protected static string|BackedEnum|null $navigationIcon = 'myicon-orgunit';

    public static function form(Schema $schema): Schema
    {
        return OrgUnitForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OrgUnitsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\JobPositionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOrgUnits::route('/'),
            'create' => CreateOrgUnit::route('/create'),
            'edit' => EditOrgUnit::route('/{record}/edit'),
        ];
    }
}

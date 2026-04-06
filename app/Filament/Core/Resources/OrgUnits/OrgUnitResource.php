<?php

declare(strict_types=1);

namespace App\Filament\Core\Resources\OrgUnits;

use App\Filament\Core\Resources\OrgUnits\Pages\CreateOrgUnit;
use App\Filament\Core\Resources\OrgUnits\Pages\EditOrgUnit;
use App\Filament\Core\Resources\OrgUnits\Pages\ListOrgUnits;
use App\Filament\Core\Resources\OrgUnits\Schemas\OrgUnitForm;
use App\Filament\Core\Resources\OrgUnits\Tables\OrgUnitsTable;
use BackedEnum;
use Bites\Core\Organization\OrgUnit;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class OrgUnitResource extends Resource
{
    protected static ?string $model = OrgUnit::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

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
            //
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

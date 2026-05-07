<?php

declare(strict_types=1);

namespace App\Filament\Lms\Resources\Materials;

use App\Filament\Lms\Resources\Materials\Pages\CreateMaterial;
use App\Filament\Lms\Resources\Materials\Pages\EditMaterial;
use App\Filament\Lms\Resources\Materials\Pages\ListMaterials;
use App\Filament\Lms\Resources\Materials\Pages\ViewMaterial;
use App\Filament\Lms\Resources\Materials\Schemas\MaterialForm;
use App\Filament\Lms\Resources\Materials\Schemas\MaterialInfolist;
use App\Filament\Lms\Resources\Materials\Tables\MaterialsTable;
use BackedEnum;
use Bites\Business\Lms\Entities\Material;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class MaterialResource extends Resource
{
    protected static ?string $model = Material::class;

    protected static string|BackedEnum|null $navigationIcon = 'myicon-book-open-duotone';

    protected static string|UnitEnum|null $navigationGroup = 'Library';

    // public static function shouldRegisterNavigation(): bool
    // {
    //     return false;
    // }

    public static function form(Schema $schema): Schema
    {
        return MaterialForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return MaterialInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MaterialsTable::configure($table);
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
            'index' => ListMaterials::route('/'),
            'create' => CreateMaterial::route('/create'),
            'view' => ViewMaterial::route('/{record}'),
            'edit' => EditMaterial::route('/{record}/edit'),
        ];
    }
}

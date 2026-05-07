<?php

declare(strict_types=1);

namespace Bites\Business\Lms\Http\UI\Admin\Resources\Materials;

use BackedEnum;
use Bites\Business\Lms\Entities\Material;
use Bites\Business\Lms\Http\UI\Admin\Resources\Materials\Pages\CreateMaterial;
use Bites\Business\Lms\Http\UI\Admin\Resources\Materials\Pages\EditMaterial;
use Bites\Business\Lms\Http\UI\Admin\Resources\Materials\Pages\ListMaterials;
use Bites\Business\Lms\Http\UI\Admin\Resources\Materials\Pages\ViewMaterial;
use Bites\Business\Lms\Http\UI\Admin\Resources\Materials\Schemas\MaterialForm;
use Bites\Business\Lms\Http\UI\Admin\Resources\Materials\Schemas\MaterialInfolist;
use Bites\Business\Lms\Http\UI\Admin\Resources\Materials\Tables\MaterialsTable;
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

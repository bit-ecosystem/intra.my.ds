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
use App\Models\Lms\Material;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MaterialResource extends Resource
{
    protected static ?string $model = Material::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

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

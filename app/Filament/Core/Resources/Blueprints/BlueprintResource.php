<?php

namespace App\Filament\Core\Resources\Blueprints;

use App\Filament\Core\Resources\Blueprints\Pages\CreateBlueprint;
use App\Filament\Core\Resources\Blueprints\Pages\EditBlueprint;
use App\Filament\Core\Resources\Blueprints\Pages\ListBlueprints;
use App\Filament\Core\Resources\Blueprints\Schemas\BlueprintForm;
use App\Filament\Core\Resources\Blueprints\Tables\BlueprintsTable;
use Bites\FilamentBlueprints\Models\Blueprint;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BlueprintResource extends Resource
{
    protected static ?string $model = Blueprint::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return BlueprintForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BlueprintsTable::configure($table);
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
            'index' => ListBlueprints::route('/'),
            'create' => CreateBlueprint::route('/create'),
            'edit' => EditBlueprint::route('/{record}/edit'),
        ];
    }
}

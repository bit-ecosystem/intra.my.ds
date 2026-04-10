<?php

declare(strict_types=1);

namespace Bites\Base\Blueprint\Resources\Blueprints;

use BackedEnum;
use Bites\Base\Blueprint\Blueprint;
use Bites\Base\Blueprint\Resources\Blueprints\Pages\CreateBlueprint;
use Bites\Base\Blueprint\Resources\Blueprints\Pages\EditBlueprint;
use Bites\Base\Blueprint\Resources\Blueprints\Pages\ListBlueprints;
use Bites\Base\Blueprint\Resources\Blueprints\Schemas\BlueprintForm;
use Bites\Base\Blueprint\Resources\Blueprints\Tables\BlueprintsTable;
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

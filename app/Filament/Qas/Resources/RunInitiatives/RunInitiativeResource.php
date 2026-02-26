<?php

declare(strict_types=1);

namespace App\Filament\Qas\Resources\RunInitiatives;

use App\Filament\Qas\Resources\RunInitiatives\Pages\CreateRunInitiative;
use App\Filament\Qas\Resources\RunInitiatives\Pages\EditRunInitiative;
use App\Filament\Qas\Resources\RunInitiatives\Pages\ListRunInitiatives;
use App\Filament\Qas\Resources\RunInitiatives\Pages\ViewRunInitiative;
use App\Filament\Qas\Resources\RunInitiatives\Schemas\RunInitiativeForm;
use App\Filament\Qas\Resources\RunInitiatives\Schemas\RunInitiativeInfolist;
use App\Filament\Qas\Resources\RunInitiatives\Tables\RunInitiativesTable;
use App\Models\Qas\RunInitiative;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class RunInitiativeResource extends Resource
{
    protected static ?string $model = RunInitiative::class;

    protected static string|UnitEnum|null $navigationGroup = 'Quality Tools';

    protected static ?string $title = 'Quality Tools';

    protected static ?int $navigationSort = 9;

    // protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return RunInitiativeForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return RunInitiativeInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RunInitiativesTable::configure($table);
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
            'index' => ListRunInitiatives::route('/'),
            'create' => CreateRunInitiative::route('/create'),
            'view' => ViewRunInitiative::route('/{record}'),
            'edit' => EditRunInitiative::route('/{record}/edit'),
        ];
    }
}

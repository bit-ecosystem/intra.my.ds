<?php

declare(strict_types=1);

namespace App\Filament\Qas\Resources\Methodologies;

use App\Filament\Qas\Resources\Methodologies\Pages\CreateMethodology;
use App\Filament\Qas\Resources\Methodologies\Pages\EditMethodology;
use App\Filament\Qas\Resources\Methodologies\Pages\ListMethodologies;
use App\Filament\Qas\Resources\Methodologies\Pages\ViewMethodology;
use App\Filament\Qas\Resources\Methodologies\Schemas\MethodologyForm;
use App\Filament\Qas\Resources\Methodologies\Schemas\MethodologyInfolist;
use App\Filament\Qas\Resources\Methodologies\Tables\MethodologiesTable;
use App\Models\Qas\Methodology;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class MethodologyResource extends Resource
{
    protected static ?string $model = Methodology::class;

    protected static string|UnitEnum|null $navigationGroup = 'Quality Tools';

    protected static ?string $title = 'Quality Tools';

    protected static ?int $navigationSort = 9;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return MethodologyForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return MethodologyInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MethodologiesTable::configure($table);
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
            'index' => ListMethodologies::route('/'),
            'create' => CreateMethodology::route('/create'),
            'view' => ViewMethodology::route('/{record}'),
            'edit' => EditMethodology::route('/{record}/edit'),
        ];
    }
}

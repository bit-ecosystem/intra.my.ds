<?php

declare(strict_types=1);

namespace App\Filament\Erp\Resources\Workflow\Turtles;

use App\Filament\Erp\Resources\Workflow\Turtles\Pages\CreateTurtle;
use App\Filament\Erp\Resources\Workflow\Turtles\Pages\EditTurtle;
use App\Filament\Erp\Resources\Workflow\Turtles\Pages\ListTurtles;
use App\Filament\Erp\Resources\Workflow\Turtles\Schemas\TurtleForm;
use App\Filament\Erp\Resources\Workflow\Turtles\Tables\TurtlesTable;
use BackedEnum;
use Bites\Workflow\Models\Turtle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class TurtleResource extends Resource
{
    protected static ?string $model = Turtle::class;

    protected static ?int $navigationSort = 51;

    protected static string|BackedEnum|null $navigationIcon = 'myicon-c-turtle';

    protected static string|UnitEnum|null $navigationGroup = 'Process Management';

    public static function form(Schema $schema): Schema
    {
        return TurtleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TurtlesTable::configure($table);
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
            'index' => ListTurtles::route('/'),
            'create' => CreateTurtle::route('/create'),
            'edit' => EditTurtle::route('/{record}/edit'),
        ];
    }
}

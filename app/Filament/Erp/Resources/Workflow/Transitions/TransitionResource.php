<?php

declare(strict_types=1);

namespace App\Filament\Erp\Resources\Workflow\Transitions;

use App\Filament\Erp\Resources\Workflow\Transitions\Pages\CreateTransition;
use App\Filament\Erp\Resources\Workflow\Transitions\Pages\EditTransition;
use App\Filament\Erp\Resources\Workflow\Transitions\Pages\ListTransitions;
use App\Filament\Erp\Resources\Workflow\Transitions\Schemas\TransitionForm;
use App\Filament\Erp\Resources\Workflow\Transitions\Tables\TransitionsTable;
use App\Models\Workflow\Transition;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class TransitionResource extends Resource
{
    protected static ?string $model = Transition::class;

    protected static ?int $navigationSort = 54;

    protected static string|BackedEnum|null $navigationIcon = 'myicon-w-transition';

    protected static string|UnitEnum|null $navigationGroup = 'Process Management';

    public static function form(Schema $schema): Schema
    {
        return TransitionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TransitionsTable::configure($table);
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
            'index' => ListTransitions::route('/'),
            'create' => CreateTransition::route('/create'),
            'edit' => EditTransition::route('/{record}/edit'),
        ];
    }
}

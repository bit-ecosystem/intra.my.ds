<?php

declare(strict_types=1);

namespace App\Filament\Erp\Resources\Workflow\Activities;

use App\Filament\Erp\Resources\Workflow\Activities\Pages\CreateActivity;
use App\Filament\Erp\Resources\Workflow\Activities\Pages\EditActivity;
use App\Filament\Erp\Resources\Workflow\Activities\Pages\ListActivities;
use App\Filament\Erp\Resources\Workflow\Activities\Schemas\ActivityForm;
use App\Filament\Erp\Resources\Workflow\Activities\Tables\ActivitiesTable;
use Bites\Workflow\Models\Activity;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class ActivityResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static string|BackedEnum|null $navigationIcon = 'myicon-c-run';

    protected static ?int $navigationSort = 56;

    protected static string|UnitEnum|null $navigationGroup = 'Process Management';

    public static function form(Schema $schema): Schema
    {
        return ActivityForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ActivitiesTable::configure($table);
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
            'index' => ListActivities::route('/'),
            'create' => CreateActivity::route('/create'),
            'edit' => EditActivity::route('/{record}/edit'),
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\Filament\Hrm\Resources\JobPositions;

use App\Filament\Hrm\Resources\JobPositions\Pages\CreateJobPosition;
use App\Filament\Hrm\Resources\JobPositions\Pages\EditJobPosition;
use App\Filament\Hrm\Resources\JobPositions\Pages\ListJobPositions;
use App\Filament\Hrm\Resources\JobPositions\Schemas\JobPositionForm;
use App\Filament\Hrm\Resources\JobPositions\Tables\JobPositionsTable;
use App\Models\Hrm\JobPosition;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class JobPositionResource extends Resource
{
    protected static ?string $model = JobPosition::class;

    protected static string|BackedEnum|null $navigationIcon = 'myicon-c-chair';

    protected static string|UnitEnum|null $navigationGroup = 'Workforce Management';

    protected static ?string $modelLabel = 'Job Positions';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return JobPositionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return JobPositionsTable::configure($table);
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
            'index' => ListJobPositions::route('/'),
            'create' => CreateJobPosition::route('/create'),
            'edit' => EditJobPosition::route('/{record}/edit'),
        ];
    }
}

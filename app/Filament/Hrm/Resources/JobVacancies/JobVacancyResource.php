<?php

declare(strict_types=1);

namespace App\Filament\Hrm\Resources\JobVacancies;

use App\Filament\Hrm\Resources\JobVacancies\Pages\CreateJobVacancy;
use App\Filament\Hrm\Resources\JobVacancies\Pages\EditJobVacancy;
use App\Filament\Hrm\Resources\JobVacancies\Pages\ListJobVacancies;
use App\Filament\Hrm\Resources\JobVacancies\Schemas\JobVacancyForm;
use App\Filament\Hrm\Resources\JobVacancies\Tables\JobVacanciesTable;
use BackedEnum;
use Bites\Employment\Models\JobVacancy;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class JobVacancyResource extends Resource
{
    protected static ?string $model = JobVacancy::class;

    protected static string|BackedEnum|null $navigationIcon = 'myicon-careers';

    protected static string|UnitEnum|null $navigationGroup = 'Workforce Management';

    protected static ?string $modelLabel = 'Job Vacancies';

    protected static ?int $navigationSort = 10;

    public static function form(Schema $schema): Schema
    {
        return JobVacancyForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return JobVacanciesTable::configure($table);
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
            'index' => ListJobVacancies::route('/'),
            'create' => CreateJobVacancy::route('/create'),
            'edit' => EditJobVacancy::route('/{record}/edit'),
        ];
    }
}

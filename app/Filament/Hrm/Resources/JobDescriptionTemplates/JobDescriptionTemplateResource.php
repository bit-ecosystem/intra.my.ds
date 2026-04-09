<?php

declare(strict_types=1);

namespace App\Filament\Hrm\Resources\JobDescriptionTemplates;

use App\Filament\Hrm\Resources\JobDescriptionTemplates\Pages\CreateJobDescriptionTemplate;
use App\Filament\Hrm\Resources\JobDescriptionTemplates\Pages\EditJobDescriptionTemplate;
use App\Filament\Hrm\Resources\JobDescriptionTemplates\Pages\ListJobDescriptionTemplates;
use App\Filament\Hrm\Resources\JobDescriptionTemplates\Schemas\JobDescriptionTemplateForm;
use App\Filament\Hrm\Resources\JobDescriptionTemplates\Tables\JobDescriptionTemplatesTable;
use BackedEnum;
use Bites\Employment\Models\JobDescriptionTemplate;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class JobDescriptionTemplateResource extends Resource
{
    protected static ?string $model = JobDescriptionTemplate::class;

    protected static string|BackedEnum|null $navigationIcon = 'myicon-description';

    protected static string|UnitEnum|null $navigationGroup = 'Workforce Management';

    protected static ?string $modelLabel = 'Job Descriptions';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return JobDescriptionTemplateForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return JobDescriptionTemplatesTable::configure($table);
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
            'index' => ListJobDescriptionTemplates::route('/'),
            'create' => CreateJobDescriptionTemplate::route('/create'),
            'edit' => EditJobDescriptionTemplate::route('/{record}/edit'),
        ];
    }
}

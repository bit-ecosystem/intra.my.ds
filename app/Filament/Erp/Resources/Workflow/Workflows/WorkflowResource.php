<?php

declare(strict_types=1);

namespace App\Filament\Erp\Resources\Workflow\Workflows;

use App\Filament\Erp\Resources\Workflow\Workflows\Pages\CreateWorkflow;
use App\Filament\Erp\Resources\Workflow\Workflows\Pages\EditWorkflow;
use App\Filament\Erp\Resources\Workflow\Workflows\Pages\ListWorkflows;
use App\Filament\Erp\Resources\Workflow\Workflows\Schemas\WorkflowForm;
use App\Filament\Erp\Resources\Workflow\Workflows\Tables\WorkflowsTable;
use App\Models\Workflow\Workflow;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class WorkflowResource extends Resource
{
    protected static ?string $model = Workflow::class;

    protected static ?int $navigationSort = 52;

    protected static string|BackedEnum|null $navigationIcon = 'myicon-c-workflow';

    protected static string|UnitEnum|null $navigationGroup = 'Process Management';

    public static function form(Schema $schema): Schema
    {
        return WorkflowForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WorkflowsTable::configure($table);
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
            'index' => ListWorkflows::route('/'),
            'create' => CreateWorkflow::route('/create'),
            'edit' => EditWorkflow::route('/{record}/edit'),
        ];
    }
}

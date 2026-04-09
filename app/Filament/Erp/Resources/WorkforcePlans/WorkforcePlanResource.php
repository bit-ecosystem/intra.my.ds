<?php

declare(strict_types=1);

namespace App\Filament\Erp\Resources\WorkforcePlans;

use App\Filament\Erp\Resources\WorkforcePlans\Pages\CreateWorkforcePlan;
use App\Filament\Erp\Resources\WorkforcePlans\Pages\EditWorkforcePlan;
use App\Filament\Erp\Resources\WorkforcePlans\Pages\ListWorkforcePlans;
use App\Filament\Erp\Resources\WorkforcePlans\Schemas\WorkforcePlanForm;
use App\Filament\Erp\Resources\WorkforcePlans\Tables\WorkforcePlansTable;
use BackedEnum;
use Bites\Employment\Models\WorkforcePlan;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class WorkforcePlanResource extends Resource
{
    protected static ?string $model = WorkforcePlan::class;

    protected static string|BackedEnum|null $navigationIcon = 'myicon-workforce';

    protected static string|UnitEnum|null $navigationGroup = 'Workforce Management';

    protected static ?string $modelLabel = 'Workforce Plans';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return WorkforcePlanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WorkforcePlansTable::configure($table);
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
            'index' => ListWorkforcePlans::route('/'),
            'create' => CreateWorkforcePlan::route('/create'),
            'edit' => EditWorkforcePlan::route('/{record}/edit'),
        ];
    }
}

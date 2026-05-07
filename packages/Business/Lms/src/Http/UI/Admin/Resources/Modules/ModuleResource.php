<?php

declare(strict_types=1);

namespace Bites\Business\Lms\Http\UI\Admin\Resources\Modules;

use BackedEnum;
use Bites\Business\Lms\Entities\Module;
use Bites\Business\Lms\Http\UI\Admin\Resources\Modules\Pages\CreateModule;
use Bites\Business\Lms\Http\UI\Admin\Resources\Modules\Pages\EditModule;
use Bites\Business\Lms\Http\UI\Admin\Resources\Modules\Pages\ListModules;
use Bites\Business\Lms\Http\UI\Admin\Resources\Modules\Pages\ViewModule;
use Bites\Business\Lms\Http\UI\Admin\Resources\Modules\Schemas\ModuleForm;
use Bites\Business\Lms\Http\UI\Admin\Resources\Modules\Schemas\ModuleInfolist;
use Bites\Business\Lms\Http\UI\Admin\Resources\Modules\Tables\ModulesTable;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class ModuleResource extends Resource
{
    protected static ?string $model = Module::class;

    protected static string|BackedEnum|null $navigationIcon = 'myicon-modules';

    protected static string|UnitEnum|null $navigationGroup = 'Classroom';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return ModuleForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ModuleInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ModulesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\MaterialsRelationManager::class,
            RelationManagers\QuizzesRelationManager::class,
            RelationManagers\EvaluationsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListModules::route('/'),
            'create' => CreateModule::route('/create'),
            'view' => ViewModule::route('/{record}'),
            'edit' => EditModule::route('/{record}/edit'),
        ];
    }
}

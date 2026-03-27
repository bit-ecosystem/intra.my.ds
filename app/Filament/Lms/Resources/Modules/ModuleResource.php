<?php

declare(strict_types=1);

namespace App\Filament\Lms\Resources\Modules;

use App\Filament\Lms\Resources\Modules\Pages\CreateModule;
use App\Filament\Lms\Resources\Modules\Pages\EditModule;
use App\Filament\Lms\Resources\Modules\Pages\ListModules;
use App\Filament\Lms\Resources\Modules\Pages\ViewModule;
use App\Filament\Lms\Resources\Modules\Schemas\ModuleForm;
use App\Filament\Lms\Resources\Modules\Schemas\ModuleInfolist;
use App\Filament\Lms\Resources\Modules\Tables\ModulesTable;
use BackedEnum;
use Bites\Kbm\Lms\Models\Module;
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
    // public static function shouldRegisterNavigation(): bool
    // {
    //     return false;
    // }

    // public static function getRecordRouteKeyName(): ?string
    // {
    //     return 'slug';
    // }

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

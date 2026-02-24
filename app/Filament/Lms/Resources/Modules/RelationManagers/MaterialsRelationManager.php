<?php

declare(strict_types=1);

namespace App\Filament\Lms\Resources\Modules\RelationManagers;

use App\Filament\Lms\Resources\Materials\MaterialResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class MaterialsRelationManager extends RelationManager
{
    protected static string $relationship = 'materials';

    protected static ?string $relatedResource = MaterialResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->recordUrl(fn (Model $model): string => ($model->url))
            ->openRecordUrlInNewTab()
            ->headerActions([
                CreateAction::make(),
            ]);
    }

    public static function getTabComponent(Model $module, string $pageClass): Tab
    {
        return Tab::make('Materials')
            ->badge($module->materials->count())
            ->icon('myicon-learning-materials')
            ->badgeTooltip('The number of materials in this category');
    }
}

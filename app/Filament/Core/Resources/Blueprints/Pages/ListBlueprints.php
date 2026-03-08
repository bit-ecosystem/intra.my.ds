<?php

namespace App\Filament\Core\Resources\Blueprints\Pages;

use App\Filament\Core\Resources\Blueprints\BlueprintResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBlueprints extends ListRecords
{
    protected static string $resource = BlueprintResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

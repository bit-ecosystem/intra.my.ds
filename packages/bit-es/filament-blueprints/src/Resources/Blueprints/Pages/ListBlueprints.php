<?php

namespace Bites\FilamentBlueprints\Resources\Blueprints\Pages;

use Bites\FilamentBlueprints\Resources\Blueprints\BlueprintResource;
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

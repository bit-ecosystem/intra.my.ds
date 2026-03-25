<?php

namespace Bites\FilamentBlueprints\Resources\Blueprints\Pages;

use Bites\FilamentBlueprints\Resources\Blueprints\BlueprintResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBlueprint extends EditRecord
{
    protected static string $resource = BlueprintResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

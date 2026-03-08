<?php

namespace App\Filament\Core\Resources\Blueprints\Pages;

use App\Filament\Core\Resources\Blueprints\BlueprintResource;
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

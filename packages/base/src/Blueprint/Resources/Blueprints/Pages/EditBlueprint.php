<?php

declare(strict_types=1);

namespace Bites\Base\Blueprint\Resources\Blueprints\Pages;

use Bites\Base\Blueprint\Resources\Blueprints\BlueprintResource;
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

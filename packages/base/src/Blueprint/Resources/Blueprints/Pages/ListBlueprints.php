<?php

declare(strict_types=1);

namespace Bites\Base\Blueprint\Resources\Blueprints\Pages;

use Bites\Base\Blueprint\Resources\Blueprints\BlueprintResource;
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

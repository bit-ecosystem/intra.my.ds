<?php

declare(strict_types=1);

namespace Bites\Base\Blueprint\Resources\Blueprints\Pages;

use Bites\Base\Blueprint\Resources\Blueprints\BlueprintResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBlueprint extends CreateRecord
{
    protected static string $resource = BlueprintResource::class;
}

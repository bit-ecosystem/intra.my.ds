<?php

declare(strict_types=1);

namespace App\Filament\Erp\Resources\Workflow\Turtles\Pages;

use App\Filament\Erp\Resources\Workflow\Turtles\TurtleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTurtle extends CreateRecord
{
    protected static string $resource = TurtleResource::class;
}

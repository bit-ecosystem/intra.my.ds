<?php

declare(strict_types=1);

namespace App\Filament\Erp\Resources\Workflow\Turtles\Pages;

use App\Filament\Erp\Resources\Workflow\Turtles\TurtleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTurtles extends ListRecords
{
    protected static string $resource = TurtleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

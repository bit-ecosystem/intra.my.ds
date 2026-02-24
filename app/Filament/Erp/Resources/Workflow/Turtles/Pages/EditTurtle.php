<?php

declare(strict_types=1);

namespace App\Filament\Erp\Resources\Workflow\Turtles\Pages;

use App\Filament\Erp\Resources\Workflow\Turtles\TurtleResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTurtle extends EditRecord
{
    protected static string $resource = TurtleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

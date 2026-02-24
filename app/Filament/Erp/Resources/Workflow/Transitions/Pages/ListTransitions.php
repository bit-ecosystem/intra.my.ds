<?php

declare(strict_types=1);

namespace App\Filament\Erp\Resources\Workflow\Transitions\Pages;

use App\Filament\Erp\Resources\Workflow\Transitions\TransitionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTransitions extends ListRecords
{
    protected static string $resource = TransitionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

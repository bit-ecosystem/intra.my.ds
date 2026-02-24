<?php

declare(strict_types=1);

namespace App\Filament\Qas\Resources\RunInitiatives\Pages;

use App\Filament\Qas\Resources\RunInitiatives\RunInitiativeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRunInitiatives extends ListRecords
{
    protected static string $resource = RunInitiativeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

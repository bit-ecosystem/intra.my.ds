<?php

declare(strict_types=1);

namespace App\Filament\Qas\Resources\RunInitiatives\Pages;

use App\Filament\Qas\Resources\RunInitiatives\RunInitiativeResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewRunInitiative extends ViewRecord
{
    protected static string $resource = RunInitiativeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\Filament\Qas\Resources\RunInitiatives\Pages;

use App\Filament\Qas\Resources\RunInitiatives\RunInitiativeResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditRunInitiative extends EditRecord
{
    protected static string $resource = RunInitiativeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}

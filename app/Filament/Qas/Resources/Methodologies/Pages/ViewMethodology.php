<?php

declare(strict_types=1);

namespace App\Filament\Qas\Resources\Methodologies\Pages;

use App\Filament\Qas\Resources\Methodologies\MethodologyResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewMethodology extends ViewRecord
{
    protected static string $resource = MethodologyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

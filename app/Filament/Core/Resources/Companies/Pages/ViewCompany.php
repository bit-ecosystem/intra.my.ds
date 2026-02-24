<?php

declare(strict_types=1);

namespace App\Filament\Core\Resources\Companies\Pages;

use App\Filament\Core\Resources\Companies\CompanyResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCompany extends ViewRecord
{
    protected static string $resource = CompanyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            //    \App\Filament\Core\Resources\HelpPages\Actions\OpenHelpAction::make(static::class, $this->record),
        ];
    }
}

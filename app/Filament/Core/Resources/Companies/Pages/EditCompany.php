<?php

declare(strict_types=1);

namespace App\Filament\Core\Resources\Companies\Pages;

use App\Filament\Core\Resources\Companies\CompanyResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditCompany extends EditRecord
{
    protected static string $resource = CompanyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            // \App\Filament\Core\Resources\HelpPages\Actions\OpenHelpAction::make(static::class, $this->record),
        ];
    }
}

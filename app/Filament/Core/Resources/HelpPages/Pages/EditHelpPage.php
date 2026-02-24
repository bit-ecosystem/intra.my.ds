<?php

declare(strict_types=1);

namespace App\Filament\Core\Resources\HelpPages\Pages;

use App\Filament\Core\Resources\HelpPages\HelpPageResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditHelpPage extends EditRecord
{
    protected static string $resource = HelpPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

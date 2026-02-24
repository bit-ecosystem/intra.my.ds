<?php

declare(strict_types=1);

namespace App\Filament\Core\Resources\HelpPages\Pages;

use App\Filament\Core\Resources\HelpPages\HelpPageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListHelpPages extends ListRecords
{
    protected static string $resource = HelpPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

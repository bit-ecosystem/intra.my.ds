<?php

declare(strict_types=1);

namespace Bites\Organization\Identity\Resources\AuthCodes\Pages;

use Bites\Organization\Identity\Resources\AuthCodes\AuthCodeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAuthCodes extends ListRecords
{
    protected static string $resource = AuthCodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

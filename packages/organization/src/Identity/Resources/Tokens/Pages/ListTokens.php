<?php

declare(strict_types=1);

namespace Bites\Organization\Identity\Resources\Tokens\Pages;

use Bites\Organization\Identity\Resources\Tokens\TokenResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTokens extends ListRecords
{
    protected static string $resource = TokenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

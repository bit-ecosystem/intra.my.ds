<?php

declare(strict_types=1);

namespace Bites\Core\Identity\Resources\RefreshTokens\Pages;

use Bites\Core\Identity\Resources\RefreshTokens\RefreshTokenResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRefreshTokens extends ListRecords
{
    protected static string $resource = RefreshTokenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

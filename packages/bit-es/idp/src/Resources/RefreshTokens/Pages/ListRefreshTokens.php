<?php

declare(strict_types=1);

namespace Bites\Idp\Resources\RefreshTokens\Pages;

use Bites\Idp\Resources\RefreshTokens\RefreshTokenResource;
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

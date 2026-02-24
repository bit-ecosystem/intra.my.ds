<?php

declare(strict_types=1);

namespace Bites\Idp\Resources\RefreshTokens\Pages;

use Bites\Idp\Resources\RefreshTokens\RefreshTokenResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRefreshToken extends EditRecord
{
    protected static string $resource = RefreshTokenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

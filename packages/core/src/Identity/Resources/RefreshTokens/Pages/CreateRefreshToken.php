<?php

declare(strict_types=1);

namespace Bites\Core\Identity\Resources\RefreshTokens\Pages;

use Bites\Core\Identity\Resources\RefreshTokens\RefreshTokenResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRefreshToken extends CreateRecord
{
    protected static string $resource = RefreshTokenResource::class;
}

<?php

declare(strict_types=1);

namespace Bites\Organization\Identity\Resources\RefreshTokens\Pages;

use Bites\Organization\Identity\Resources\RefreshTokens\RefreshTokenResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRefreshToken extends CreateRecord
{
    protected static string $resource = RefreshTokenResource::class;
}

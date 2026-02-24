<?php

declare(strict_types=1);

namespace Bites\Idp\Resources\RefreshTokens\Pages;

use Bites\Idp\Resources\RefreshTokens\RefreshTokenResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRefreshToken extends CreateRecord
{
    protected static string $resource = RefreshTokenResource::class;
}

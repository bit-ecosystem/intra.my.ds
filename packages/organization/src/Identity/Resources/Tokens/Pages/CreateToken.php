<?php

declare(strict_types=1);

namespace Bites\Organization\Identity\Resources\Tokens\Pages;

use Bites\Organization\Identity\Resources\Tokens\TokenResource;
use Filament\Resources\Pages\CreateRecord;

class CreateToken extends CreateRecord
{
    protected static string $resource = TokenResource::class;
}

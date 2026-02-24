<?php

declare(strict_types=1);

namespace Bites\Idp\Resources\Tokens\Pages;

use Bites\Idp\Resources\Tokens\TokenResource;
use Filament\Resources\Pages\CreateRecord;

class CreateToken extends CreateRecord
{
    protected static string $resource = TokenResource::class;
}

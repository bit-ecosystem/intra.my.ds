<?php

declare(strict_types=1);

namespace Bites\Organization\Identity\Resources\AuthCodes\Pages;

use Bites\Organization\Identity\Resources\AuthCodes\AuthCodeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAuthCode extends CreateRecord
{
    protected static string $resource = AuthCodeResource::class;
}

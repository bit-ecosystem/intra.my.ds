<?php

declare(strict_types=1);

namespace Bites\Core\Identity\Resources\AuthCodes\Pages;

use Bites\Core\Identity\Resources\AuthCodes\AuthCodeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAuthCode extends CreateRecord
{
    protected static string $resource = AuthCodeResource::class;
}

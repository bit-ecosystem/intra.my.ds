<?php

declare(strict_types=1);

namespace Bites\Idp\Resources\AuthCodes\Pages;

use Bites\Idp\Resources\AuthCodes\AuthCodeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAuthCode extends CreateRecord
{
    protected static string $resource = AuthCodeResource::class;
}

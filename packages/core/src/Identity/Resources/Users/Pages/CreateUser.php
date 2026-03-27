<?php

declare(strict_types=1);

namespace Bites\Core\Identity\Resources\Users\Pages;

use Bites\Core\Identity\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}

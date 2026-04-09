<?php

declare(strict_types=1);

namespace Bites\Organization\Identity\Resources\Users\Pages;

use Bites\Organization\Identity\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}

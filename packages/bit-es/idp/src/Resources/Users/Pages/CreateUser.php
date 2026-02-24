<?php

declare(strict_types=1);

namespace Bites\Idp\Resources\Users\Pages;

use Bites\Idp\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}

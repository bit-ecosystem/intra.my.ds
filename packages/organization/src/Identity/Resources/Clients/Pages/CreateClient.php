<?php

declare(strict_types=1);

namespace Bites\Organization\Identity\Resources\Clients\Pages;

use Bites\Organization\Identity\Resources\Clients\ClientResource;
use Filament\Resources\Pages\CreateRecord;

class CreateClient extends CreateRecord
{
    protected static string $resource = ClientResource::class;
}

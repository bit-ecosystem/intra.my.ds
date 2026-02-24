<?php

declare(strict_types=1);

namespace Bites\Idp\Resources\Clients\Pages;

use Bites\Idp\Resources\Clients\ClientResource;
use Filament\Resources\Pages\CreateRecord;

class CreateClient extends CreateRecord
{
    protected static string $resource = ClientResource::class;
}

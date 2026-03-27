<?php

declare(strict_types=1);

namespace Bites\Core\Identity\Resources\DeviceCodes\Pages;

use Bites\Core\Identity\Resources\DeviceCodes\DeviceCodeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDeviceCode extends CreateRecord
{
    protected static string $resource = DeviceCodeResource::class;
}

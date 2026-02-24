<?php

declare(strict_types=1);

namespace Bites\Idp\Resources\DeviceCodes\Pages;

use Bites\Idp\Resources\DeviceCodes\DeviceCodeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDeviceCode extends CreateRecord
{
    protected static string $resource = DeviceCodeResource::class;
}

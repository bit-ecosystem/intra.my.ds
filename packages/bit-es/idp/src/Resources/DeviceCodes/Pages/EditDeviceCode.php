<?php

declare(strict_types=1);

namespace Bites\Idp\Resources\DeviceCodes\Pages;

use Bites\Idp\Resources\DeviceCodes\DeviceCodeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDeviceCode extends EditRecord
{
    protected static string $resource = DeviceCodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

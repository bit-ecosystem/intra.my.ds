<?php

declare(strict_types=1);

namespace Bites\Idp\Resources\DeviceCodes\Pages;

use Bites\Idp\Resources\DeviceCodes\DeviceCodeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDeviceCodes extends ListRecords
{
    protected static string $resource = DeviceCodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

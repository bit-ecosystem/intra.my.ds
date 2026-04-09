<?php

declare(strict_types=1);

namespace Bites\Organization\Identity\Resources\Clients\Pages;

use Bites\Organization\Identity\Resources\Clients\ClientResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditClient extends EditRecord
{
    protected static string $resource = ClientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

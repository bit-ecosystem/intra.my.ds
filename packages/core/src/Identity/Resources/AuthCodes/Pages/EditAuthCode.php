<?php

declare(strict_types=1);

namespace Bites\Core\Identity\Resources\AuthCodes\Pages;

use Bites\Core\Identity\Resources\AuthCodes\AuthCodeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAuthCode extends EditRecord
{
    protected static string $resource = AuthCodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

<?php

declare(strict_types=1);

namespace Bites\Idp\Resources\AuthCodes\Pages;

use Bites\Idp\Resources\AuthCodes\AuthCodeResource;
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

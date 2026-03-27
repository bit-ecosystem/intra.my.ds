<?php

declare(strict_types=1);

namespace Bites\Core\Identity\Resources\Tokens\Pages;

use Bites\Core\Identity\Resources\Tokens\TokenResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditToken extends EditRecord
{
    protected static string $resource = TokenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

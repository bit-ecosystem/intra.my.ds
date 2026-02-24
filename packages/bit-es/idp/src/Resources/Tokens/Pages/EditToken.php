<?php

declare(strict_types=1);

namespace Bites\Idp\Resources\Tokens\Pages;

use Bites\Idp\Resources\Tokens\TokenResource;
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

<?php

declare(strict_types=1);

namespace App\Filament\Erp\Resources\Workflow\Requests\Pages;

use App\Filament\Erp\Resources\Workflow\Requests\RequestResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRequest extends EditRecord
{
    protected static string $resource = RequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

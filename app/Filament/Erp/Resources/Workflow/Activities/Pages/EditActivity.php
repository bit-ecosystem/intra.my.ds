<?php

declare(strict_types=1);

namespace App\Filament\Erp\Resources\Workflow\Activities\Pages;

use App\Filament\Erp\Resources\Workflow\Activities\ActivityResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditActivity extends EditRecord
{
    protected static string $resource = ActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

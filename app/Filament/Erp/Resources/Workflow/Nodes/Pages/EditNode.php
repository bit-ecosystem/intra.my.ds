<?php

declare(strict_types=1);

namespace App\Filament\Erp\Resources\Workflow\Nodes\Pages;

use App\Filament\Erp\Resources\Workflow\Nodes\NodeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditNode extends EditRecord
{
    protected static string $resource = NodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\Filament\Erp\Resources\Workflow\Workflows\Pages;

use App\Filament\Erp\Resources\Workflow\Workflows\WorkflowResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditWorkflow extends EditRecord
{
    protected static string $resource = WorkflowResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

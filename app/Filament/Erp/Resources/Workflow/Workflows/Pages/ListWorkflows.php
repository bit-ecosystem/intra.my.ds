<?php

declare(strict_types=1);

namespace App\Filament\Erp\Resources\Workflow\Workflows\Pages;

use App\Filament\Erp\Resources\Workflow\Workflows\WorkflowResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWorkflows extends ListRecords
{
    protected static string $resource = WorkflowResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

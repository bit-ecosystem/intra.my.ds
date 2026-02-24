<?php

declare(strict_types=1);

namespace App\Filament\Erp\Resources\Workflow\Workflows\Pages;

use App\Filament\Erp\Resources\Workflow\Workflows\WorkflowResource;
use Filament\Resources\Pages\CreateRecord;

class CreateWorkflow extends CreateRecord
{
    protected static string $resource = WorkflowResource::class;
}

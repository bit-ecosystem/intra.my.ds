<?php

declare(strict_types=1);

namespace App\Filament\Erp\Resources\Workflow\Nodes\Pages;

use App\Filament\Erp\Resources\Workflow\Nodes\NodeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateNode extends CreateRecord
{
    protected static string $resource = NodeResource::class;
}

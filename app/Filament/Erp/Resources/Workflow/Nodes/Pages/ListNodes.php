<?php

declare(strict_types=1);

namespace App\Filament\Erp\Resources\Workflow\Nodes\Pages;

use App\Filament\Erp\Resources\Workflow\Nodes\NodeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListNodes extends ListRecords
{
    protected static string $resource = NodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

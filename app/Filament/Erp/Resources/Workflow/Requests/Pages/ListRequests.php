<?php

declare(strict_types=1);

namespace App\Filament\Erp\Resources\Workflow\Requests\Pages;

use App\Filament\Erp\Resources\Workflow\Requests\RequestResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRequests extends ListRecords
{
    protected static string $resource = RequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

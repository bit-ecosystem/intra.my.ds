<?php

declare(strict_types=1);

namespace App\Filament\Erp\Resources\Workflow\Activities\Pages;

use App\Filament\Erp\Resources\Workflow\Activities\ActivityResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListActivities extends ListRecords
{
    protected static string $resource = ActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

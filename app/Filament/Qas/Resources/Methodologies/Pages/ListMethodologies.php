<?php

declare(strict_types=1);

namespace App\Filament\Qas\Resources\Methodologies\Pages;

use App\Filament\Qas\Resources\Methodologies\MethodologyResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMethodologies extends ListRecords
{
    protected static string $resource = MethodologyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Core\Resources\EipProposals\Pages;

use App\Filament\Core\Resources\EipProposals\EipProposalResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEipProposals extends ListRecords
{
    protected static string $resource = EipProposalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

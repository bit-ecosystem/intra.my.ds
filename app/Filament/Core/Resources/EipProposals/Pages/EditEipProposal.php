<?php

declare(strict_types=1);

namespace App\Filament\Core\Resources\EipProposals\Pages;

use App\Filament\Core\Resources\EipProposals\EipProposalResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEipProposal extends EditRecord
{
    protected static string $resource = EipProposalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

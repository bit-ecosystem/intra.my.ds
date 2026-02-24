<?php

declare(strict_types=1);

namespace App\Filament\Dms\Resources\Documents\Pages;

use App\Filament\Dms\Resources\Documents\DocumentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDocuments extends ListRecords
{
    protected static string $resource = DocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getSubheading(): ?string
    {
        return __('Documentation, manuals, guides, procedures, policies, and other knowledge resources for staff.');
    }
}

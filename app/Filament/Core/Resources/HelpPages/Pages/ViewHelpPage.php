<?php

declare(strict_types=1);

namespace App\Filament\Core\Resources\HelpPages\Pages;

use App\Filament\Core\Resources\HelpPages\HelpPageResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewHelpPage extends ViewRecord
{
    protected static string $resource = HelpPageResource::class;

    public function getSubheading(): ?string
    {
        return __('Custom Page Heading');
    }

    public function getTitle(): string|Htmlable
    {
        return $this->record->title ?: __('Test Page Title');

    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

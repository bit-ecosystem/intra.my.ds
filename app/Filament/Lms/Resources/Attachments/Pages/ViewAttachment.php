<?php

declare(strict_types=1);

namespace App\Filament\Lms\Resources\Attachments\Pages;

use App\Filament\Lms\Resources\Attachments\AttachmentResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Enums\Width;

class ViewAttachment extends ViewRecord
{
    protected static string $resource = AttachmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

    public function getMaxContentWidth(): Width
    {
        return Width::ScreenTwoExtraLarge;
    }
}

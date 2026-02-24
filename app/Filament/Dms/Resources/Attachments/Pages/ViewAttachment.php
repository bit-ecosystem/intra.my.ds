<?php

declare(strict_types=1);

namespace App\Filament\Dms\Resources\Attachments\Pages;

use App\Filament\Dms\Resources\Attachments\AttachmentResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewAttachment extends ViewRecord
{
    protected static string $resource = AttachmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

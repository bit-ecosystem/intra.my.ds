<?php

declare(strict_types=1);

namespace App\Filament\Dms\Resources\Attachments\Pages;

use App\Filament\Dms\Resources\Attachments\AttachmentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAttachment extends CreateRecord
{
    protected static string $resource = AttachmentResource::class;
}

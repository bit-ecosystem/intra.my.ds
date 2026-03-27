<?php

declare(strict_types=1);

namespace App\Filament\Lms\Resources\Attachments\Pages;

use App\Filament\Lms\Resources\Attachments\AttachmentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAttachment extends CreateRecord
{
    protected static string $resource = AttachmentResource::class;
}

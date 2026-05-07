<?php

declare(strict_types=1);

namespace Bites\Business\Lms\Http\UI\Staff\Resources\Attachments\Pages;

use Bites\Business\Lms\Http\UI\Staff\Resources\Attachments\AttachmentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAttachment extends CreateRecord
{
    protected static string $resource = AttachmentResource::class;
}

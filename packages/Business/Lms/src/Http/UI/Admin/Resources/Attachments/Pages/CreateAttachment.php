<?php

declare(strict_types=1);

namespace Bites\Business\Lms\Http\UI\Admin\Resources\Attachments\Pages;

use Bites\Business\Lms\Http\UI\Admin\Resources\Attachments\AttachmentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAttachment extends CreateRecord
{
    protected static string $resource = AttachmentResource::class;
}

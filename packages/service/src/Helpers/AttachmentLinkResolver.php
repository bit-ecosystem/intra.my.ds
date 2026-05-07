<?php

declare(strict_types=1);

namespace Bites\Service\Helpers;

use Bites\Business\Lms\Http\UI\Staff\Resources\Attachments\AttachmentResource;
use Illuminate\Support\Str;

final class AttachmentLinkResolver
{
    public static function isLocal(string $path): bool
    {
        return ! Str::startsWith($path, ['http://', 'https://']);
    }

    public static function isSharePoint(string $path): bool
    {
        // dd($path);
        return Str::contains($path, '.sharepoint.com');
    }

    public static function shouldUseInternalViewer(string $path): bool
    {
        return self::isLocal($path); // || self::isSharePoint($path);
    }

    public static function recordUrl($record): string
    {
        if (self::shouldUseInternalViewer($record->file_path)) {
            return AttachmentResource::getUrl(
                'view',
                ['record' => $record]
            );
        }

        return $record->file_path;
    }

    public static function openInNewTab(string $path): bool
    {
        return ! self::shouldUseInternalViewer($path);
    }
}

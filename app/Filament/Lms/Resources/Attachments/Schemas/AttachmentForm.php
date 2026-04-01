<?php

declare(strict_types=1);

namespace App\Filament\Lms\Resources\Attachments\Schemas;

use Filament\Forms\Components;
use Filament\Schemas\Schema;
use Bites\Shared\Concerns\HasAttachableExtLink;

class AttachmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Components\TextInput::make('file_name'),
                HasAttachableExtLink::FormComponent(),
                Components\FileUpload::make('file_path')
                    ->disk('public')
                    ->directory('doc-attachments')
                    ->visibility('public'),
            ]);
    }
}

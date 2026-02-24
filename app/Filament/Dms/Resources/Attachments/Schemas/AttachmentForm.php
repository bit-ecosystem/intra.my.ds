<?php

declare(strict_types=1);

namespace App\Filament\Dms\Resources\Attachments\Schemas;

use Filament\Forms\Components;
use Filament\Schemas\Schema;

class AttachmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Components\TextInput::make('file_name'),
                Components\FileUpload::make('file_path')
                    ->disk('public')
                    ->directory('doc-attachments')
                    ->visibility('public'),
            ]);
    }
}

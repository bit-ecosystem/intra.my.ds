<?php

declare(strict_types=1);

namespace App\Filament\Lms\Resources\Attachments\Schemas;

use Filament\Infolists\Components\ViewEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AttachmentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Document')
                ->description('Zoom in/out use Ctrl + Mouse Wheel or pinch on touch devices.')
                ->columnSpanFull()

                // ✅ Alpine state lives on the Section
                ->extraAlpineAttributes([
                    'x-data' => '{
                        zoom: 1,
                        zoomMin: 0.5,
                        zoomMax: 3,
                        zoomStep: 0.1,
                        containerHeight: 300,
                    }',
                ])

                ->schema([
                    ViewEntry::make('file_path')
                        ->view('filament.infolists.components.pdf-view-entry')
                        ->columnSpanFull(),
                ]),
        ]);
    }
}

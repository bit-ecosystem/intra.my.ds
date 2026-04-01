<?php

declare(strict_types=1);

namespace App\Filament\Lms\Resources\Attachments\Tables;

use App\Filament\Lms\Resources\Attachments\AttachmentResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AttachmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('file_name'),
                TextColumn::make('type'),
            ])

            // ✅ Make the entire row clickable
            ->recordUrl(fn (Model $record): ?string => Str::startsWith($record->file_path, 'doc-attachments')
                   ? AttachmentResource::getUrl('view', ['record' => $record])
                    : $record->file_path
            )

            // ✅ Open external URLs in new tab
            ->openRecordUrlInNewTab(fn (Model $record): bool => ! Str::startsWith($record->file_path, 'doc-attachments')
            )

            ->recordActions([
                // ViewAction::make()
                //     ->visible(fn (Model $record) =>
                //         Str::startsWith($record->file_path, 'doc-attachments')
                //     ),

                EditAction::make(),

                // Action::make('media-url')
                //     ->label('Open File')
                //     ->url(fn (Model $record) => $record->file_path)
                //     ->openUrlInNewTab()
                //     ->visible(fn (Model $record) =>
                //         ! Str::startsWith($record->file_path, 'doc-attachments')
                //     ),
            ]);
    }
}

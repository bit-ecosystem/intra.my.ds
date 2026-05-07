<?php

declare(strict_types=1);

namespace Bites\Business\Lms\Http\UI\Admin\Resources\Attachments\Tables;

use Bites\Service\Helpers\AttachmentLinkResolver;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

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

            ->recordUrl(
                fn (Model $record): ?string => AttachmentLinkResolver::recordUrl($record)
            )

            // ✅ Open external URLs in new tab
            ->openRecordUrlInNewTab(
                fn (Model $record): bool => AttachmentLinkResolver::openInNewTab($record->file_path)
            )

            ->recordActions([
                EditAction::make(),
            ]);
    }
}

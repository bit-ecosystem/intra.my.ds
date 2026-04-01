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

            ->recordUrl(
                fn(Model $record): ?string =>
                \Bites\Shared\Helpers\AttachmentLinkResolver::recordUrl($record)
            )

            // ✅ Open external URLs in new tab
            ->openRecordUrlInNewTab(
                fn(Model $record): bool =>
                \Bites\Shared\Helpers\AttachmentLinkResolver::openInNewTab($record->file_path)
            )

            ->recordActions([
                EditAction::make(),
            ]);
    }
}

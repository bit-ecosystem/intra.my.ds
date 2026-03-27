<?php

declare(strict_types=1);

namespace App\Filament\Lms\Resources\Attachments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\Action;
use Filament\Schemas\Components\View;
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
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('media-url')
                    ->url(fn ($record) => $record->file_path ?? '#')
                    ->openUrlInNewTab(),
            ])
            // ->recordUrl(fn (Model $model): string => ($model->file_path))
            // ->openRecordUrlInNewTab()
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

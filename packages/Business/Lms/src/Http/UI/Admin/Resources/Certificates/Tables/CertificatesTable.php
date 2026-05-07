<?php

declare(strict_types=1);

namespace Bites\Business\Lms\Http\UI\Admin\Resources\Certificates\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CertificatesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('module.title')->searchable(),
                TextColumn::make('name')->label('Name')->getStateUsing(fn ($record) => $record->user?->name ?? $record->staff?->name ?? '-'),
                TextColumn::make('quiz_attempt_id')->numeric()->sortable(),
                TextColumn::make('certificate_number')->searchable(),
                TextColumn::make('title')->searchable(),
                TextColumn::make('issued_at')->dateTime()->sortable(),
                TextColumn::make('expires_at')->dateTime()->sortable(),
                TextColumn::make('status')->searchable(),
                TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

<?php

declare(strict_types=1);

namespace App\Filament\Hrm\Resources\Staff\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns;
use Filament\Tables\Table;

class StaffTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // Columns\Layout\Split::make([
                // Columns\Layout\Stack::make([
                Columns\TextColumn::make('user.name')
                    ->searchable(),
                Columns\TextColumn::make('staff_number')->searchable()->sortable()->copyable(),
                Columns\TextColumn::make('jobPosition.title')
                    ->searchable(),
                // ]),
                // ]),
                Columns\TextColumn::make('orgUnit.code')
                    ->searchable(),
                Columns\TextColumn::make('name')->searchable()->sortable(),
                Columns\TextColumn::make('shift_code')
                    ->searchable(),

                Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

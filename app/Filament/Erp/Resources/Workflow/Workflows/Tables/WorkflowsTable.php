<?php

declare(strict_types=1);

namespace App\Filament\Erp\Resources\Workflow\Workflows\Tables;

use App\Models\Workflow\Workflow;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;

class WorkflowsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultGroup('turtle.code')
            ->groups([
                Group::make('turtle.code')
                    ->getDescriptionFromRecordUsing(fn (Workflow $record): string => $record->turtle->name),
            ])
            ->columns([
                // TextColumn::make('turtle.name')
                //     ->searchable(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
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

<?php

declare(strict_types=1);

namespace App\Filament\Core\Resources\RoleMappers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\QueryBuilder;
use Filament\QueryBuilder\Constraints\NumberConstraint;

class RoleMappersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('scope')
                    ->searchable(),
                TextColumn::make('role_name')
                    ->searchable(),
                TextColumn::make('ou.code')
                    ->sortable(),
                // TextColumn::make('org_unit_id')
                //     ->sortable(),
                TextColumn::make('staff_count')->counts('staff')
                    ->listWithLineBreaks()
                    ->sortable(),
                IconColumn::make('enabled')
                    ->boolean(),
                TextColumn::make('label')
                    ->searchable(),
                TextColumn::make('category')
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
                // QueryBuilder::make()
                //     ->constraints([
                //         NumberConstraint::make('staff')
                //     ])
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

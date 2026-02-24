<?php

declare(strict_types=1);

namespace Bites\Idp\Resources\Tokens\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns;
use Filament\Tables\Table;

class TokensTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Columns\TextColumn::make('client.name')->sortable(),
                Columns\TextColumn::make('user_id')->sortable(),
                Columns\TextColumn::make('client.name')->sortable(),
                Columns\TextColumn::make('scopes')->sortable(),
                Columns\IconColumn::make('revoked')->boolean(),
                Columns\TextColumn::make('expires_at')
                    ->dateTime()
                    ->sortable(),
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

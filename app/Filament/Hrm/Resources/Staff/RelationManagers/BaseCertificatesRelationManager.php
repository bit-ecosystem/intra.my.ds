<?php

namespace App\Filament\Hrm\Resources\Staff\RelationManagers;

use Filament\Actions;
use Filament\Tables;
use Filament\Resources\RelationManagers\RelationManager;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Lms\Resources\Certificates\Schemas\CertificateInfolist;

abstract class BaseCertificatesRelationManager extends RelationManager
{
    protected static string $relationship = 'certificates';

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->recordTitleAttribute('certificate_number')
            ->columns([
                Tables\Columns\TextColumn::make('certificate_number')
                    ->label('Certificate No')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('title')
                    ->wrap()
                    ->searchable(),

                Tables\Columns\TextColumn::make('module.name')
                    ->label('Module')
                    ->badge(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'success' => 'valid',
                        'warning' => 'expired',
                        'danger'  => 'revoked',
                    ]),

                Tables\Columns\TextColumn::make('issued_at')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('expires_at')
                    ->date()
                    ->sortable(),
            ])
            ->recordActions([
                Actions\ViewAction::make()

                    ->modalHeading('Certificate')
                    ->modalWidth('4xl')
                    ->schema(
                        fn($schema) =>
                        CertificateInfolist::configure($schema)
                    ),

            ])
            ->defaultSort('issued_at', 'desc');
    }
}

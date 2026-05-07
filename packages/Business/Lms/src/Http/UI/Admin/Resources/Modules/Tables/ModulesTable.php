<?php

declare(strict_types=1);

namespace Bites\Business\Lms\Http\UI\Admin\Resources\Modules\Tables;

use Bites\Business\Lms\Entities\Module;
use Bites\Business\Lms\Http\UI\Admin\Resources\Modules\ModuleResource;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class ModulesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(
                Module::query()// ->visibleTo(Auth::user())
            )
            ->columns([
                TextColumn::make('title'),
                TextColumn::make('slug'),
                TextColumn::make('description'),
                TextColumn::make('estimated_duration_minutes')
                    ->label('Duration')
                    ->numeric()
                    ->suffix('mins')
                    ->sortable(),
                TextColumn::make('validity_months')
                    ->label('Valid for')
                    ->numeric()
                    ->suffix('mths')
                    ->sortable(),
            ])
            // ->recordUrl(fn ($record) => ModuleResource::getUrl('view', ['record' => $record->slug]))
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

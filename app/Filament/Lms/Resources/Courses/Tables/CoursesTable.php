<?php

declare(strict_types=1);

namespace App\Filament\Lms\Resources\Courses\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use App\Enums\CourseGroup;

class CoursesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(
                \App\Models\Lms\Course::query()->where('status', 'published')
            )
            ->columns([
                TextColumn::make('title')
                    ->wrap()
                    ->searchable(),
                TextColumn::make('description')
                    ->wrap()
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'warning' => 'draft',
                        'success' => 'published',
                        'gray' => 'archived',
                    ]),
                TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable(),
                // TextColumn::make('created_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
                // TextColumn::make('updated_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
            ])

            ->filters([
                // SelectFilter::make('category')
                //     ->label('Category')
                //     ->live(debounce: 300)
                //     ->options(collect(CourseGroup::cases())->mapWithKeys(fn($c) => [$c->value => $c->getLabel()])),
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

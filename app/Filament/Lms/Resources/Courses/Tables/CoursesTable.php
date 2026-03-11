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
use App\Filament\Tables\Columns\CategoryGroupColumn;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\ImageColumn;
use Filament\Support\Enums;
use Filament\Tables\Columns\IconColumn;

class CoursesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(
                \App\Models\Lms\Course::query()->where('status', 'published')
            )
            ->columns([
                TextColumn::make('category')
                    ->label('Group')
                    ->badge()
                    ->formatStateUsing(fn(?CourseGroup $state) => $state?->getLabel() ?? '-')
                    ->color(fn(?CourseGroup $state) => $state?->getColor())
                    ->tooltip(fn(?CourseGroup $state) => $state?->getDescription()),
                Split::make([
                           IconColumn::make('category')
                    ->label('')
                    ->icon(fn(?CourseGroup $state) => $state?->getIcon() ?? 'heroicon-o-tag')
                    ->color(fn(?CourseGroup $state) => $state?->getColor())
                    ->tooltip(fn(?CourseGroup $state) => $state?->getDescription())
                    ->sortable(false)
                    ->grow(false),
                    Stack::make([
                        TextColumn::make('title')
                            ->label('Title')
                            ->searchable()
                            ->weight(Enums\FontWeight::SemiBold)
                            ->color(fn($record) => $record->category?->getColor())
                            ->tooltip(fn($record) => $record->category?->getDescription()),
                        TextColumn::make('description')
                            ->size(Enums\TextSize::ExtraSmall)
                            ->searchable()
                            ->wrap(),
                    ]),
                ]),
            ])
            ->paginated(false)
            ->contentGrid([
                'md' => 1,
                'xl' => 4,
            ]);
    }
}

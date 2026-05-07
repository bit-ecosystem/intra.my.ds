<?php

declare(strict_types=1);

namespace App\Filament\Lms\Resources\Courses\Tables;

use App\Enums\CourseGroup;
use Bites\Business\Lms\Entities\Course;
use Filament\Support\Enums;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class CoursesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(
                Course::query()->where('status', 'published')->withCount('modules') // ->visibleTo(Auth::user())
            )
            ->columns([
                TextColumn::make('category')
                    ->label('Group')
                    ->badge()
                    ->alignEnd()
                    ->formatStateUsing(function (?CourseGroup $state, $record) {
                        $label = $state?->getLabel() ?? '-';
                        $count = $record->modules_count ?? 0;
                        if ($count === 0) {
                            return $label;
                        }

                        return "{$label} · {$count} ".str('module')->plural($count);
                    })
                    ->color(fn (?CourseGroup $state) => $state?->getColor())
                    ->tooltip(fn (?CourseGroup $state) => $state?->getDescription()),
                Split::make([
                    IconColumn::make('category')
                        ->label('')
                        ->icon(fn (?CourseGroup $state): string|\BackedEnum|\Illuminate\Contracts\Support\Htmlable => $state?->getIcon() ?? 'heroicon-o-tag')
                        ->color(fn (?CourseGroup $state): string|array|null => $state?->getColor())
                        ->tooltip(fn (?CourseGroup $state): string|\Illuminate\Contracts\Support\Htmlable|null => $state?->getDescription())
                        ->sortable(false)
                        ->grow(false),
                    Stack::make([
                        TextColumn::make('title')
                            ->label('Title')
                            ->searchable()
                            ->weight(Enums\FontWeight::SemiBold)
                            ->color(fn ($record) => $record->category?->getColor())
                            ->tooltip(fn ($record) => $record->category?->getDescription()),
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

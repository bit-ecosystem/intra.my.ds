<?php

declare(strict_types=1);

namespace Bites\Business\Lms\Http\UI\Admin\Resources\QuizAttempts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\QueryBuilder\Constraints;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Table;

class QuizAttemptsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('module.title')->searchable(),
                TextColumn::make('quiz.name')->searchable()->sortable(),

                TextColumn::make('examiner.staff_number')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(function ($state, $record) {
                        // $state is examiner.staff_number
                        $examinerNo = $state;
                        $staffNo = $record->staff?->staff_number;

                        // Show examiner number only if it's different; otherwise show '-'
                        return ($examinerNo && $examinerNo !== $staffNo) ? $examinerNo : '-';
                    }),

                TextColumn::make('staff.staff_number')->searchable()->copyable(),
                TextColumn::make('name')->label('Name')->getStateUsing(fn ($record) => $record->staff?->name ?? $record->user?->name ?? '-'),
                TextColumn::make('started_at')->dateTime()->sortable(),
                IconColumn::make('result')->boolean()->trueIcon(Heroicon::OutlinedCheckBadge)->falseIcon(Heroicon::OutlinedXMark),
                TextColumn::make('score')->numeric()->sortable(),
                TextColumn::make('time_taken')
                    ->sortable()
                    ->toggleable()
                    ->formatStateUsing(
                        fn ($record): string => $record->time_taken === null
                            ? '—'
                            : number_format((float) $record->time_taken, 1).'s'
                    ),
                TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                QueryBuilder::make()
                    ->constraints([
                        Constraints\TextConstraint::make('quiz.name'),
                        Constraints\NumberConstraint::make('time_taken'),
                        Constraints\NumberConstraint::make('score'),
                        Constraints\DateConstraint::make('started_at'),
                    ]),
            ], layout: FiltersLayout::BeforeContentCollapsible)
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
        //     ->modifyQueryUsing(function ($query) {
        //         $query
        //             // Join quizzes so we can sort by l_quizzes.name
        //             ->leftJoin('l_quizzes', 'l_quizzes.id', '=', 'l_quiz_attempts.quiz_id')

        //             // 1) quiz.name ASC
        //             ->orderBy('l_quizzes.name', 'asc')

        //             // 2) score DESC
        //             ->orderBy('l_quiz_attempts.score', 'desc')

        //             // 3) duration ASC, with incomplete attempts (NULLs) at the end
        //             ->orderByRaw("
        //     CASE
        //         WHEN l_quiz_attempts.started_at IS NULL
        //           OR l_quiz_attempts.completed_at IS NULL
        //         THEN 1 ELSE 0
        //     END ASC,
        //     TIMESTAMPDIFF(SECOND, l_quiz_attempts.started_at, l_quiz_attempts.completed_at) ASC
        // ")

        //             // Re-select base model columns to avoid ambiguity
        //             ->select('l_quiz_attempts.*');
        //     });
    }
}

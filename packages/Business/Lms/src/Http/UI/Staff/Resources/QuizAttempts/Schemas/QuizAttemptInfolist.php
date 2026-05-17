<?php

declare(strict_types=1);

namespace Bites\Business\Lms\Http\UI\Staff\Resources\QuizAttempts\Schemas;

use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class QuizAttemptInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Overview')
                    ->schema([
                        Grid::make(3)->schema([

                            TextEntry::make('quiz.title')
                                ->label('Quiz')
                                ->placeholder('-')
                                ->weight('bold'),

                            TextEntry::make('module.title')
                                ->label('Module')
                                ->placeholder('-'),

                            TextEntry::make('result')
                                ->badge()
                                ->color(fn (string|null $state) => match ($state) {
                                    'pass' => 'success',
                                    'fail' => 'danger',
                                    'pending' => 'warning',
                                    'incomplete' => 'gray',
                                    default => 'gray',
                                })
                                ->placeholder('-'),
                        ]),
                    ]),

                Section::make('Performance')
                    ->schema([
                        Grid::make(3)->schema([

                            TextEntry::make('score')
                                ->label('Score (%)')
                                ->numeric(2)
                                ->suffix('%')
                                ->color(fn ($state) => $state >= 80 ? 'success' : ($state >= 50 ? 'warning' : 'danger'))
                                ->placeholder('-')
                                ->weight('bold'),

                            TextEntry::make('time_taken')
                                ->label('Time Taken')
                                ->formatStateUsing(fn ($state) => $state ? "{$state} mins" : '-'),

                            TextEntry::make('started_at')
                                ->label('Started')
                                ->dateTime('d M Y, H:i')
                                ->placeholder('-'),
                        ]),
                    ]),

                Section::make('Participants')
                    ->schema([
                        Grid::make(2)->schema([

                            TextEntry::make('staff.name')
                                ->label('Staff (Taker)')
                                ->placeholder('-'),

                            TextEntry::make('examiner.name')
                                ->label('Examiner')
                                ->placeholder('-'),

                            TextEntry::make('user.name')
                                ->label('Created By (User)')
                                ->placeholder('-'),

                        ]),
                    ]),

                Section::make('Metadata')
                    ->collapsible()
                    ->schema([
                        Grid::make(2)->schema([

                            TextEntry::make('id')
                                ->label('Attempt ID'),

                            TextEntry::make('created_at')
                                ->dateTime('d M Y, H:i'),

                            TextEntry::make('updated_at')
                                ->dateTime('d M Y, H:i'),

                        ]),
                    ]),

            ]);
    }
}
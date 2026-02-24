<?php

declare(strict_types=1);

namespace App\Filament\Lms\Resources\Quizzes\Schemas;

use Filament\Forms;
use Filament\Schemas;
use App\Filament\Lms\Resources\Modules\RelationManagers\QuizzesRelationManager;
use Filament\Forms\Components\Slider\Enums\PipsMode;
use Filament\Support\RawJs;

class QuizForm
{
    public static function configure(Schemas\Schema $schema): Schemas\Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('name')
                    ->label('Form Name')
                    ->required(),

                Forms\Components\Select::make('module_id')
                    ->label('Module')
                    ->relationship('module', 'title') // assumes Quiz belongsTo Module, and Module has 'name'
                    ->searchable()
                    ->preload()
                    ->hidden(fn($livewire) => $livewire instanceof QuizzesRelationManager)
                    ->required(fn($livewire) => ! ($livewire instanceof QuizzesRelationManager)),
                Forms\Components\Slider::make('passing_mark')
                    ->range(minValue: 0, maxValue: 100)
                    ->pips(PipsMode::Positions)
                    ->tooltips(RawJs::make(<<<'JS'
        `${$value.toFixed(0)}%`
        JS))
                    ->decimalPlaces(0)
                    ->pipsValues([0, 25, 50, 75, 100]),
                // Forms\Components\TextInput::make('passing_mark')
                //     ->required(),
                // Forms\Components\Toggle::make('quiz_style')
                //     ->offIcon('myicon-q-selftake')
                //     ->onIcon('myicon-q-examiner'),
                Forms\Components\Checkbox::make('examiner_style')
                    ->inline(),
                Forms\Components\Builder::make('schema')
                    ->label('Form Schema')
                    ->blocks([
                        Forms\Components\Builder\Block::make('quiz')
                            ->label('Quiz Question')
                            ->schema([
                                Schemas\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\Hidden::make('name')->label('Field Name'),
                                        Schemas\Components\Section::make([
                                            Forms\Components\TextInput::make('label')->required()->label('Question'),
                                            Forms\Components\Textarea::make('description')->label('More details'),
                                            Forms\Components\FileUpload::make('image')->image()->disk('public')
                                                ->directory('quiz')
                                                ->visibility('public'),
                                        ]),

                                        Forms\Components\Builder::make('options')
                                            ->label('Answer Options')
                                            ->blocks([
                                                Forms\Components\Builder\Block::make('option')
                                                    ->schema([
                                                        Schemas\Components\Grid::make(2) // 2 columns
                                                            ->schema([
                                                                Forms\Components\Hidden::make('key'),
                                                                Forms\Components\TextInput::make('value')
                                                                    ->hiddenLabel()
                                                                    ->required(),
                                                                Forms\Components\Toggle::make('correct')
                                                                    ->label('Correct'),
                                                            ]),
                                                    ]),

                                            ]),

                                    ]),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}

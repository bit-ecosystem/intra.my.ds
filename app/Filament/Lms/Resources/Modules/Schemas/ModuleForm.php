<?php

declare(strict_types=1);

namespace App\Filament\Lms\Resources\Modules\Schemas;

use Bites\Business\Lms\Entities\Course;
use Bites\Service\Components\StakeholderInput;
use Filament\Forms\Components;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ModuleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([

                /* ============================================
                 | Core Information
                 |============================================ */
                Section::make('Module Details')
                    ->description('Basic information about this learning module')
                    ->icon('myicon-modules')
                    ->columns(3)
                    ->columnSpanFull()
                    ->components([

                        Components\TextInput::make('title')
                            ->label('Module Title')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->helperText('Clear, descriptive title shown to learners'),

                        Components\TextInput::make('order_index')
                            ->label('Module Order')
                            ->numeric()
                            ->default(1)
                            ->helperText('Position inside a course'),
                        Components\TextInput::make('slug')
                            ->required()
                            ->disabledOn('edit')
                            ->helperText('Automatically generated identifier'),

                        Components\Textarea::make('description')
                            ->rows(4)
                            ->columnSpanFull()
                            ->helperText('Brief overview of this module'),
                    ]),
                Section::make('Audience & Permissions')
                    ->icon('myicon-p-hrm')
                    ->components([
                        StakeholderInput::make()
                            ->columnSpanFull(),
                    ])->columnSpanFull(),
                Section::make('Test/Quiz Settings')
                    ->icon('heroicon-o-clock')
                    ->columns(3)
                    ->components([
                        Components\TextInput::make('estimated_duration_minutes')
                            ->label('Duration')
                            ->suffix('minutes')
                            ->numeric()
                            ->default(60),
                        Components\TextInput::make('validity_months')
                            ->label('Validity')
                            ->suffix('months')
                            ->numeric()
                            ->default(12),
                        Components\Textarea::make('certificate_template')
                            ->label('Certificate Template (JSON)')
                            ->rows(5)
                            ->columnSpanFull()
                            ->helperText('Advanced users only'),
                    ]),
                Section::make('Courses')
                    ->description('Attach this module to one or more courses')
                    ->icon('myicon-course')
                    ->components([
                        Components\Select::make('courses')
                            ->relationship(
                                'courses',
                                'title',
                                fn ($query) => $query->orderBy('category', 'asc')->orderBy('title', 'asc')
                            )
                            ->getOptionLabelFromRecordUsing(
                                fn (Course $course) => "{$course->category?->label()} · {$course->title}"
                            )
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->helperText('Modules can appear in multiple courses')
                            ->columnSpanFull(),

                    ]),

            ]);
    }
}

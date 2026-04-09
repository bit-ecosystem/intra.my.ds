<?php

declare(strict_types=1);

namespace App\Filament\Lms\Resources\Courses\Schemas;

use Bites\Service\Components\StakeholderInput;
use Filament\Forms\Components;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Bites\Knowledge\Learning\Module;

class CourseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            // ->columns(3)
            ->components([

                /* ============================================
                 | Core Information
                 |============================================ */
                Section::make('Course Details')
                    ->description('Basic information that identifies this course')
                    ->icon('myicon-course')
                    ->columns(3)
                    ->columnSpanFull()
                    ->components([

                        Components\TextInput::make('code')
                            ->label('Course Code')
                            ->required()
                            ->maxLength(50)
                            ->helperText('Short unique identifier (e.g. LMS-SEC-101)'),


                        Components\TextInput::make('title')
                            ->label('Course Title')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->helperText('Displayed to learners across the platform')
                            ->columnSpan(2),

                        Components\Textarea::make('description')
                            ->label('Description')
                            ->rows(4)
                            ->columnSpanFull()
                            ->helperText('Brief overview of what learners will gain'),
                    ]),
                /* ============================================
                 | Audience & Permissions
                 |============================================ */
                Section::make('Audience & Permissions')
                    ->description('Define who can view or manage this course')
                    ->icon('myicon-p-hrm')

                    ->extraAttributes([
                        'class' => 'border-l-4 border-blue-500 pl-4',
                    ])

                    ->components([
                        StakeholderInput::make()
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
                /* ============================================
                 | Publishing & Lifecycle
                 |============================================ */
                Section::make('Publishing')
                    ->description('Control course visibility and lifecycle')
                    ->icon('heroicon-o-megaphone')
                    ->columns(3)
                    ->components([
                        Components\Select::make('status')
                            ->options([
                                'draft'     => 'Draft',
                                'published' => 'Published',
                                'archived'  => 'Archived',
                            ])
                            ->required()
                            ->default('draft')
                            ->helperText('Draft courses are not visible to learners'),

                        Components\DateTimePicker::make('published_at')
                            ->label('Publish Date')
                            ->seconds(false)
                            ->helperText('Optional scheduled publishing time'),

                    ]),

      Section::make('Modules')
                    ->description('Attach this module to one or more courses')
                    ->icon('myicon-course')
                    ->components([
                        Components\Select::make('courses')
                            ->relationship(
                                'modules',
                                'title',
                                fn ($query) => $query->orderBy('category', 'asc')->orderBy('title', 'asc')
                            )
                            ->getOptionLabelFromRecordUsing(
                                fn (Module $module) => "{$module->title} · {$module->description}"
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

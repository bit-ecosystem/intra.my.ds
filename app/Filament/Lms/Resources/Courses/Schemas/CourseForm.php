<?php

declare(strict_types=1);

namespace App\Filament\Lms\Resources\Courses\Schemas;

use Filament\Forms\Components;
use Filament\Schemas\Schema;

class CourseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Components\TextInput::make('code')
                    ->required(),
                Components\TextInput::make('title')
                    ->required(),
                Components\Textarea::make('description')
                    ->columnSpanFull(),
                Components\Select::make('status')
                    ->options(['draft' => 'Draft', 'published' => 'Published', 'archived' => 'Archived'])
                    ->required(),
                Components\Repeater::make('stakeHolder')
                    ->relationship()
                    ->schema([
                        Components\Select::make('role_id')
                            ->relationship('role', 'name'),

                        Components\Toggle::make('can_view'),
                        Components\Toggle::make('can_edit'),
                    ]),
                Components\DateTimePicker::make('published_at'),
            ]);
    }
}

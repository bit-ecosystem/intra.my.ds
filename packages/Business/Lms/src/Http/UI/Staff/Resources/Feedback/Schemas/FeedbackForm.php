<?php

namespace Bites\Business\Lms\Http\UI\Staff\Resources\Feedback\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class FeedbackForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('evaluation_id')
                    ->relationship('evaluation', 'name')
                    ->required(),
                Textarea::make('data')
                    ->required()
                    ->columnSpanFull(),
                Select::make('user_id')
                    ->relationship('user', 'name'),
                TextInput::make('for_staff')
                    ->numeric(),
                TextInput::make('by_staff')
                    ->numeric(),
                Select::make('module_id')
                    ->relationship('module', 'title')
                    ->required(),
                DateTimePicker::make('started_at'),
                TextInput::make('time_taken')
                    ->numeric(),
            ]);
    }
}

<?php

namespace App\Filament\Lms\Resources\Evaluations\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class EvaluationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('module_id')
                    ->relationship('module', 'title')
                    ->required(),
                TextInput::make('code')
                    ->required(),
                TextInput::make('name'),
                Toggle::make('is_active')
                    ->required(),
                Textarea::make('schema')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}

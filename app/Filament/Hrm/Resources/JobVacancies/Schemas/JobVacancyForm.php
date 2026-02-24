<?php

declare(strict_types=1);

namespace App\Filament\Hrm\Resources\JobVacancies\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class JobVacancyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('job_position_id')
                    ->required()
                    ->numeric(),
                TextInput::make('location')
                    ->required(),
                Textarea::make('responsibilities')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('qualifications')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('salary_range'),
                TextInput::make('status')
                    ->required()
                    ->default('open'),
                TextInput::make('posted_by')
                    ->required()
                    ->numeric(),
            ]);
    }
}

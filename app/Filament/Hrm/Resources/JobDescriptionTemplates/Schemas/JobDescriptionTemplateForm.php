<?php

declare(strict_types=1);

namespace App\Filament\Hrm\Resources\JobDescriptionTemplates\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class JobDescriptionTemplateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                Textarea::make('description')
                    ->required()
                    ->rows(5)
                    ->columnSpanFull(),
                Textarea::make('attributes')
                    ->columnSpanFull(),
                TextInput::make('masco_code'),
            ]);
    }
}

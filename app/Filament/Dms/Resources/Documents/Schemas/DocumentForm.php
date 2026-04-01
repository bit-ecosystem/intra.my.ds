<?php

declare(strict_types=1);

namespace App\Filament\Dms\Resources\Documents\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DocumentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('org_unit_id')
                    ->relationship('orgUnit', 'name'),
                Select::make('document_type_id')
                    ->relationship('level', 'name')
                    ->required(),
                Select::make('classification_id')
                    ->relationship('classification', 'name')
                    ->required(),
                TextInput::make('owner_staff_id')
                    ->numeric(),
                TextInput::make('parent_id')
                    ->numeric(),
                TextInput::make('code')
                    ->required(),
                TextInput::make('title')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('file_path'),
                TextInput::make('status')
                    ->required()
                    ->default('draft'),
                DateTimePicker::make('published_at'),
                DateTimePicker::make('effective_at'),
                DateTimePicker::make('retired_at'),
            ]);
    }
}

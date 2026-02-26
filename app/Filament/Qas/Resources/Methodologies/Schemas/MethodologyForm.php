<?php

declare(strict_types=1);

namespace App\Filament\Qas\Resources\Methodologies\Schemas;

use Filament\Forms\Components;
use Filament\Schemas\Schema;

class MethodologyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Components\TextInput::make('name')->required(),
                Components\TextInput::make('slug')->required()->unique(ignoreRecord: true),
                Components\Toggle::make('is_active')->default(true),
                Components\Repeater::make('schema')->label('Sections')
                    ->schema([
                        Components\TextInput::make('label')->label('Section Label')->required(),
                        Components\Select::make('columns')
                            ->options([
                                1 => '1 Column',
                                2 => '2 Columns',
                                3 => '3 Columns',
                            ])->default(1),
                        Components\Repeater::make('fields')
                            ->schema([
                                Components\Select::make('component')
                                    ->options([
                                        'text' => 'Text',
                                        'textarea' => 'Textarea',
                                        'select' => 'Select',
                                        'date' => 'Date',
                                        'checkbox' => 'Checkbox',
                                        'file' => 'File Upload',
                                        'image' => 'Image Upload',
                                    ])->required(),
                                Components\TextInput::make('name')->required(),
                                Components\TextInput::make('label')->required(),
                                Components\Toggle::make('required'),
                                Components\KeyValue::make('options')->visible(fn ($get) => $get('component') === 'select'),
                                Components\TextInput::make('disk')->default('public')->visible(fn ($get) => in_array($get('component'), ['file', 'image'])),
                                Components\TextInput::make('directory')->visible(fn ($get) => in_array($get('component'), ['file', 'image'])),
                                Components\TagsInput::make('accepted_types')->visible(fn ($get) => $get('component') === 'file'),
                            ])
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}

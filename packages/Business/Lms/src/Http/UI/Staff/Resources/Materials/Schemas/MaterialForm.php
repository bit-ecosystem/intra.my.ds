<?php

declare(strict_types=1);

namespace Bites\Business\Lms\Http\UI\Staff\Resources\Materials\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MaterialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('module_id')
                    ->relationship('module', 'title')
                    ->required(),
                TextInput::make('title')
                    ->required(),
                TextInput::make('type')
                    ->required()
                    ->default('link'),
                TextInput::make('url')
                    ->url()
                    ->required(),
                TextInput::make('order_index')
                    ->required()
                    ->numeric()
                    ->default(0),
                Textarea::make('meta')
                    ->columnSpanFull(),
            ]);
    }
}

<?php

declare(strict_types=1);

namespace App\Filament\Staff\Resources\Menus\Schemas;

use Filament\Forms\Components;
use Filament\Schemas\Schema;

class MenuForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Components\TextInput::make('category')
                    ->required(),
                Components\TextInput::make('title')
                    ->required(),
                Components\TextInput::make('icon'),
                Components\Textarea::make('description')
                    ->columnSpanFull(),
                Components\TextInput::make('internal_link'),
                Components\TextInput::make('external_link'),
                Components\Toggle::make('is_active')
                    ->required(),
            ]);
    }
}

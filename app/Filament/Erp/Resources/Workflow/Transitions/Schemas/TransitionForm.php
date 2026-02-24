<?php

declare(strict_types=1);

namespace App\Filament\Erp\Resources\Workflow\Transitions\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TransitionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('workflow_id')
                    ->relationship('workflow', 'name')
                    ->required(),
                TextInput::make('from_state_id')
                    ->required()
                    ->numeric(),
                TextInput::make('to_state_id')
                    ->required()
                    ->numeric(),
                TextInput::make('action_name')
                    ->required(),
                TextInput::make('sort')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}

<?php

declare(strict_types=1);

namespace App\Filament\Erp\Resources\Workflow\Requests\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('workflow_id')
                    ->relationship('workflow', 'name')
                    ->required(),
                Select::make('current_state_id')
                    ->relationship('currentState', 'name'),
                TextInput::make('subject_type')
                    ->required(),
                TextInput::make('subject_id')
                    ->required()
                    ->numeric(),
                Select::make('initiator_id')
                    ->relationship('initiator', 'name'),
            ]);
    }
}

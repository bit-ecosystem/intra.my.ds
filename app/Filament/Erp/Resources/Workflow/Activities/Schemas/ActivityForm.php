<?php

declare(strict_types=1);

namespace App\Filament\Erp\Resources\Workflow\Activities\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ActivityForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('workflow_id')
                    ->relationship('workflow', 'name')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                Textarea::make('metadata')
                    ->columnSpanFull(),
                TextInput::make('activityable_type')
                    ->required(),
                TextInput::make('activityable_id')
                    ->required()
                    ->numeric(),
            ]);
    }
}

<?php

declare(strict_types=1);

namespace App\Filament\Erp\Resources\WorkforcePlans\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class WorkforcePlanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('org_unit_id')
                    ->relationship('orgUnit', 'name'),
                TextInput::make('job_title_id')
                    ->numeric(),
                TextInput::make('title')
                    ->required(),
                TextInput::make('required_quantity')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}

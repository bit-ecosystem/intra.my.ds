<?php

declare(strict_types=1);

namespace App\Filament\Erp\Resources\Workflow\Turtles\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TurtleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('org_unit_id')
                    ->relationship('orgUnit', 'name')
                    ->required(),
                TextInput::make('code')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('input'),
                TextInput::make('output'),
                Select::make('supplier_id')
                    ->relationship('supplier', 'name'),
                Select::make('customer_id')
                    ->relationship('customer', 'name'),
                Select::make('org_role_id')
                    ->relationship('orgRole', 'name'),
                TextInput::make('resources'),
                TextInput::make('methods'),
                TextInput::make('kpis'),
                Textarea::make('description')
                    ->columnSpanFull(),
            ]);
    }
}

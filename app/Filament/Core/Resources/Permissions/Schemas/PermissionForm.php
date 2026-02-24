<?php

namespace App\Filament\Core\Resources\Permissions\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Spatie\Permission\Models\Role;

class PermissionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('guard_name')
                    ->required(),
                TextInput::make('description'),
                Select::make('roles')
                    ->multiple()
                    ->preload()
                    ->relationship(titleAttribute: 'name', name: 'roles')
                    ->getOptionLabelFromRecordUsing(fn (Role $record): string => "{$record->name} - {$record->team_id}"),

            ]);
    }
}

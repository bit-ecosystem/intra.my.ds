<?php

declare(strict_types=1);

namespace App\Filament\Core\Resources\RoleMappers\Schemas;

use Bites\Core\Authorization\RoleMapper;
use Filament\Forms\Components;
use Filament\Schemas\Schema;

class RoleMapperForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Components\TextInput::make('role_name')->required(),
                Components\TextInput::make('org_unit_name')
                    ->label('for OU')
                    ->disabled()
                    ->dehydrated(false) // do not save back
                    ->afterStateHydrated(function (Components\TextInput $textInput, ?RoleMapper $roleMapper): void {
                        $textInput->state($roleMapper?->orgUnit?->name);
                    })
                    ->visible(fn (?RoleMapper $roleMapper): bool => filled($roleMapper?->orgUnit?->name)),
                Components\TextInput::make('label')->columnSpanFull(),
                // Components\TextInput::make('scope')->required(),
                // Components\TextInput::make('priority')->required()->numeric()->default(100),
                // Components\TextInput::make('category'),
                Components\Textarea::make('conditions')->columnSpanFull(),
                Components\Toggle::make('enabled')->required(),
                Components\Select::make('staff')
                    ->label('Staff')
                    ->relationship(
                        name: 'staff',
                        titleAttribute: 'name' // change to 'full_name' or whatever your column is
                    )
                    ->multiple()
                    ->preload()     // loads initial options for currently selected records
                    ->searchable(), // enables typeahead search on large datasets

            ]);
    }
}

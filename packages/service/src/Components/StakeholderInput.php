<?php

declare(strict_types=1);

namespace Bites\Service\Components;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;

class StakeholderInput
{
    public static function make(string $name = 'stakeHolder')
    {
        return Repeater::make($name)
            ->relationship()
            ->table([
                Repeater\TableColumn::make('Role'),
                Repeater\TableColumn::make('View')->alignCenter(true),
                Repeater\TableColumn::make('Edit'),

            ])
            // ->compact()
            ->label('Stakeholders')
            ->schema([
                Select::make('role_id')
                    ->relationship('role', 'name')
                    ->required(),
                Checkbox::make('can_view')
                    ->label('View'),
                Checkbox::make('can_edit')
                    ->label('Edit'),
            ])
            ->columns(3);
    }
}

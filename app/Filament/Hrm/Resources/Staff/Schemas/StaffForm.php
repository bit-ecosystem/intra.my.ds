<?php

declare(strict_types=1);

namespace App\Filament\Hrm\Resources\Staff\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class StaffForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship(
                        'user',
                        'email',
                        modifyQueryUsing: function (Builder $query, $record) {
                            return $query->whereDoesntHave('staff');
                        }
                    )
                    ->searchable()
                    ->preload(),
                TextInput::make('staff_number')
                    ->required(),
                Select::make('org_unit_id')
                    ->relationship('orgUnit', 'name'),
                Select::make('job_position_id')
                    ->relationship('jobPosition', 'title'),

                Select::make('staffRoleLinks')
                    ->label('Roles')
                    ->relationship(
                        name: 'staffRoleLinks',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn (Builder $query) => $query->where('guard_name', 'web'),
                    )
                    ->multiple()
                    ->preload()
                    ->disabled()
                    ->searchable(),

            ]);
    }
}

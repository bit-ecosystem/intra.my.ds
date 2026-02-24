<?php

declare(strict_types=1);

namespace App\Filament\Hrm\Resources\OrgUnits\RelationManagers;

use App\Filament\Hrm\Resources\OrgUnits\OrgUnitResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns;
use Filament\Tables\Table;

class JobPositionsRelationManager extends RelationManager
{
    protected static string $relationship = 'jobPositions';

    protected static ?string $relatedResource = OrgUnitResource::class;

    protected static ?string $title = 'Job Positions';

    public function table(Table $table): Table
    {
        return $table->columns([
            Columns\TextColumn::make('title')
                ->searchable(),
            Columns\TextColumn::make('staff.staff_number')
                ->label('Staff Number')
                ->searchable(),
            Columns\TextColumn::make('staff.name')
                ->label('Staff Name')
                ->label('Name')
                ->searchable(),
            Columns\TextColumn::make('user.name')
                ->label('User Name')
                ->searchable(),
        ])
            ->headerActions([
                // CreateAction::make(),
            ]);
    }
}

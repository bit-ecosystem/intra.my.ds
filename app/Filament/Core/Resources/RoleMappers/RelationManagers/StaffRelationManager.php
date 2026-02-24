<?php

declare(strict_types=1);

namespace App\Filament\Core\Resources\RoleMappers\RelationManagers;

use App\Filament\Core\Resources\RoleMappers\RoleMapperResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns;
use Filament\Tables\Table;

class StaffRelationManager extends RelationManager
{
    protected static string $relationship = 'staff';

    protected static ?string $relatedResource = RoleMapperResource::class;

    protected static ?string $title = 'Staff with this role';

    public function table(Table $table): Table
    {
        return $table->columns([
            Columns\TextColumn::make('jobPosition.title')
                ->searchable(),
            Columns\TextColumn::make('staff_number')
                ->label('Staff Number')
                ->searchable(),
            Columns\TextColumn::make('name')
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

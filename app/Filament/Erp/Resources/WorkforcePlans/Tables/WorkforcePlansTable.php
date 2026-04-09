<?php

declare(strict_types=1);

namespace App\Filament\Erp\Resources\WorkforcePlans\Tables;

use Bites\Organization\Structure\OrgUnit;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\PermissionRegistrar;

class WorkforcePlansTable
{
    public static function configure(Table $table): Table
    {
        $teamId = app(PermissionRegistrar::class)->getPermissionsTeamId();
        $orgname = OrgUnit::find($teamId)->name;
        $orgcode = OrgUnit::find($teamId)->code;

        return $table
            ->heading('Headcounts for Org Unit: '.$orgname.' ['.$orgcode.']')
            ->modifyQueryUsing(function (Builder $builder): void {
                $teamId = app(PermissionRegistrar::class)->getPermissionsTeamId();
                $builder->where('org_unit_id', $teamId);
            })
            ->columns([

                // TextColumn::make('orgUnit.name')
                //     ->searchable(),
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('required_quantity')
                    ->label('Required')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('job_positions_count')->counts('jobPositions')
                    ->label('Actual')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('job_positions_filled_count')
                    ->counts('jobPositionsFilled')
                    ->label('Filled')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])->groups([
                Group::make('orgUnit.name')
                    ->collapsible(),
                Group::make('title')
                    ->collapsible(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

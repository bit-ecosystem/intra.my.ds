<?php

declare(strict_types=1);

namespace App\Filament\Erp\Resources\Workflow\Turtles\Tables;

use Bites\Core\Organization\OrgUnit;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\PermissionRegistrar;

class TurtlesTable
{
    public static function configure(Table $table): Table
    {
        // $teamId = app(PermissionRegistrar::class)->getPermissionsTeamId();
        // $orgname = OrgUnit::find($teamId)->name;
        // $orgcode = OrgUnit::find($teamId)->code;

        return $table
            // ->heading('Processes in Org Unit: '.$orgname.' ['.$orgcode.']')
            // ->modifyQueryUsing(function (Builder $query) {
            //     $teamId = app(PermissionRegistrar::class)->getPermissionsTeamId();
            //     $query->where('org_unit_id', $teamId);
            // })
            ->columns([
                TextColumn::make('orgUnit.name')
                    ->searchable(),
                TextColumn::make('code')
                    ->searchable(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('input')
                    ->searchable(),
                TextColumn::make('output')
                    ->searchable(),
                TextColumn::make('supplier.name')
                    ->searchable(),
                TextColumn::make('customer.name')
                    ->searchable(),
                TextColumn::make('orgRole.name')
                    ->searchable(),
                TextColumn::make('resources')
                    ->searchable(),
                TextColumn::make('methods')
                    ->searchable(),
                TextColumn::make('kpis')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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

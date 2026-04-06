<?php

declare(strict_types=1);

namespace App\Filament\Erp\Resources\OrgRoles\Tables;

use Bites\Core\Organization\OrgUnit;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\PermissionRegistrar;

class OrgRolesTable
{
    public static function configure(Table $table): Table
    {
        $teamId = app(PermissionRegistrar::class)->getPermissionsTeamId();
        $orgname = OrgUnit::find($teamId)->name;
        $orgcode = OrgUnit::find($teamId)->code;

        return $table
            ->heading('Roles for Org Unit: '.$orgname.' ['.$orgcode.']')
            ->modifyQueryUsing(function (Builder $builder): void {
                $teamId = app(PermissionRegistrar::class)->getPermissionsTeamId();
                $builder->where('team_id', $teamId);
            })
            ->columns([
                TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('description')
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

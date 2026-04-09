<?php

declare(strict_types=1);

namespace App\Filament\Hrm\Resources\OrgUnits\Tables;

use Bites\Organization\Structure\OrgUnit;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrgUnitsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    // ->description(fn(OrgUnit $record): string => $record->parent?->code ? 'in ' . $record->parent->code : "")
                    ->description(fn (OrgUnit $orgUnit): string =>
                        // If both code and parent code exist: "CODE in PARENTCODE"
                        ($orgUnit->code && $orgUnit->parent?->code)
                            ? sprintf('%s %s in %s %s', $orgUnit->code, $orgUnit->type, $orgUnit->parent->code, $orgUnit->parent->type)
                        // If only parent code exists: "in PARENTCODE"
                        : ($orgUnit->parent?->code
                            ? 'in '.$orgUnit->parent->code
                            : sprintf('%s %s', $orgUnit->code, $orgUnit->type))
                    )

                    ->searchable(),
                TextColumn::make('code')
                    ->description(fn (OrgUnit $orgUnit): string =>
                            // If both code and parent code exist: "CODE in PARENTCODE"
                            ($orgUnit->name && $orgUnit->parent?->code)
                                ? sprintf('%s %s in %s', $orgUnit->name, $orgUnit->type, $orgUnit->parent->code)
                            // If only parent code exists: "in PARENTCODE"
                            : ($orgUnit->parent?->code
                                ? 'in '.$orgUnit->parent->code
                                : sprintf('%s %s', $orgUnit->name, $orgUnit->type))
                    )
                    ->searchable(),
                TextColumn::make('job_positions_count')->counts('jobPositions'),
                TextColumn::make('owner.staff.name')
                    ->label('Owner [Title]')
                    ->description(fn (OrgUnit $orgUnit): string => '['.$orgUnit->owner->title.']')
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

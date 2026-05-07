<?php

declare(strict_types=1);

namespace App\Filament\Hrm\Resources\Staff\Widgets;

use Bites\Employment\Models\Staff;
use Bites\Organization\Structure\OrgUnit;
use Filament\Actions\BulkActionGroup;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class ShiftMixByOrgUnitTable extends TableWidget
{
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Staff::query())
            // ->query($this->baseQuery())
            ->columns([
                // TextColumn::make('user.name')
                //     ->searchable(),
                // TextColumn::make('staff_number')
                //     ->searchable(),

                TextColumn::make('orgUnit.code')
                    ->searchable(),
                TextColumn::make('jobPosition.title')
                    ->searchable(),
                TextColumn::make('shift_code')
                    ->searchable(),

                TextColumn::make('staff_count')
                    ->label('Staff Count')
                    ->alignRight()
                    ->numeric()
                    ->sortable(),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    /**
     * Create tabs: All + one per Org Unit.
     */
    public function getTabs(): array
    {
        $tabs = [];

        // "All" tab — no org_unit filter
        $tabs['all'] = Tab::make('All')
            ->modifyQueryUsing(fn (Builder $query): Builder => $query);

        // Build an OrgUnit tab for each unit
        OrgUnit::query()
            ->orderBy('name')
            ->get()
            ->each(function (OrgUnit $orgUnit) use (&$tabs): void {
                $tabs[(string) $orgUnit->id] = Tab::make($orgUnit->name)
                    ->badge( // optional: show total staff in that unit
                        Staff::query()->where('org_unit_id', $orgUnit->id)->count()
                    )
                    ->modifyQueryUsing(function (Builder $query) use ($orgUnit): void {
                        // Filter the base aggregation by org_unit
                        $query->where('staff.org_unit_id', $orgUnit->id);
                    });
            });

        return $tabs;
    }

    /**
     * Base aggregation query:
     * - LEFT JOIN person_attributes for shift_code
     * - Group by shift_code
     */
    protected function baseQuery(): Builder
    {
        // NOTE: start from Staff::query() so we remain an Eloquent Builder.
        // We'll select aggregated columns only; Filament can still display them
        // as fields on each "row" (no row actions needed).
        $builder = Staff::query()
            ->leftJoin('person_attributes as pa', function ($join): void {
                $join->on('pa.attributable_id', '=', 'staff.id')
                    ->where('pa.attributable_type', '=', Staff::class)
                    ->where('pa.key', '=', 'shift_code');
            })
            ->selectRaw('COALESCE(pa.value, ?) as shift_code, COUNT(staff.id) as staff_count', ['(No shift)'])
            ->groupBy('pa.value');

        // If you want to exclude disabled staff, terminated, etc., add additional where clauses here.

        return $builder;
    }
}

<?php

declare(strict_types=1);

namespace App\Filament\Hrm\Resources\WorkforcePlans\Pages;

use App\Filament\Hrm\Resources\WorkforcePlans\WorkforcePlanResource;
use Bites\Core\Organization\Models\OrgUnit;
use Bites\Hrm\Models\WorkforcePlan;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListWorkforcePlans extends ListRecords
{
    protected static string $resource = WorkforcePlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        // Pull Org Units that actually have job positions
        $orgUnits = OrgUnit::query()->select(['id', 'code', 'name'])
            ->whereHas('jobPositions')
            ->orderBy('code')
            ->get();
        // dd($orgUnits);
        $tabs = [];

        // Default "All" tab
        $tabs['all'] = Tab::make()
            ->label('All')
            ->badge(WorkforcePlan::count());

        foreach ($orgUnits as $orgUnit) {
            $label = $orgUnit->code ?: ($orgUnit->name ?: 'OrgUnit #'.$orgUnit->id);

            $tabs[$label] = Tab::make()
                ->label($label)
                ->modifyQueryUsing(fn (Builder $query) => $query->where('org_unit_id', $orgUnit->id))
                // Show a badge count per tab
                ->badge(WorkforcePlan::where('org_unit_id', $orgUnit->id)->count());
        }

        return $tabs;
    }
}

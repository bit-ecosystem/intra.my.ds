<?php

declare(strict_types=1);

namespace App\Filament\Hrm\Resources\JobPositions\Pages;

use App\Filament\Hrm\Resources\JobPositions\JobPositionResource;
use Bites\Core\Organization\JobPosition;
use Bites\Core\Organization\OrgUnit;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListJobPositions extends ListRecords
{
    protected static string $resource = JobPositionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        // Pull Org Units that actually have job positions
        $orgUnits = OrgUnit::query()
            ->select(['id', 'code', 'name'])
            ->whereHas('jobPositions')
            ->orderBy('code')
            ->get();

        $tabs = [];

        // Default "All" tab
        $tabs['all'] = Tab::make()
            ->label('All')
            ->badge(JobPosition::count());

        foreach ($orgUnits as $orgUnit) {
            $label = $orgUnit->code ?: ($orgUnit->name ?: 'OrgUnit #'.$orgUnit->id);

            $tabs[$label] = Tab::make()
                ->label($label)
                ->modifyQueryUsing(fn (Builder $query) => $query->where('org_unit_id', $orgUnit->id))
                // Show a badge count per tab
                ->badge(JobPosition::where('org_unit_id', $orgUnit->id)->count());
        }

        return $tabs;
    }
}

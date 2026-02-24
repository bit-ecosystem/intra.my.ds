<?php

declare(strict_types=1);

namespace App\Filament\Hrm\Resources\OrgUnits\Pages;

use App\Enums\OrgUnitType;
use App\Filament\Hrm\Resources\OrgUnits\OrgUnitResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListOrgUnits extends ListRecords
{
    protected static string $resource = OrgUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        $tabs = [
            'all' => Tab::make('All'),
            // Optional: add a badge count
            // ->badge((string) Order::query()->count())
        ];

        // Iterate through all cases of the enum
        foreach (OrgUnitType::cases() as $type) {
            $tabs[$type->value] = Tab::make($type->getLabel())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('type', $type->value));
            // Optional: add a badge count for each type
            // ->badge((string) Order::query()->where('type', $type->value)->count())
        }

        return $tabs;
    }
}

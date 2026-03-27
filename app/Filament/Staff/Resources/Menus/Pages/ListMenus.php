<?php

declare(strict_types=1);

namespace App\Filament\Staff\Resources\Menus\Pages;

use App\Filament\Staff\Resources\Menus\MenuResource;
use App\Models\Menu;
use Bites\Shared\Concerns\HasHelp;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListMenus extends ListRecords
{
    protected static string $resource = MenuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            \App\Filament\Core\Resources\HelpPages\Actions\OpenHelpAction::make(static::class),
        ];
    }

    public function getTabs(): array
    {
        $categories = Menu::query()
            ->select('category')
            ->distinct()
            ->pluck('category')
            ->filter() // remove nulls if needed
            ->toArray();

        $tabs = [];

        $tabs['all'] = Tab::make(); // default tab showing all records

        foreach ($categories as $category) {
            $tabs[$category] = Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('category', $category));
        }

        return $tabs;
    }
}

<?php

declare(strict_types=1);

namespace App\Filament\Staff\Pages;

use BackedEnum;
use Filament\Pages\Page;
use UnitEnum;

class Location extends Page
{
    protected static string|UnitEnum|null $navigationGroup = 'Location';

    protected static ?string $title = 'Floor Plan';

    protected string $view = 'filament.staff.pages.location';

    protected static string|BackedEnum|null $navigationIcon = 'myicon-location';

    protected static ?int $navigationSort = 51;

    public function getSubheading(): ?string
    {
        return __('Links to floor plans and maps of the organization buildings and campuses. Ideally includes registered storage locations.');
    }
}

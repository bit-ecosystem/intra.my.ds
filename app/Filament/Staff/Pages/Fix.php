<?php

declare(strict_types=1);

namespace App\Filament\Staff\Pages;

use BackedEnum;
use Filament\Pages\Page;
use UnitEnum;

class Fix extends Page
{
    protected static string|UnitEnum|null $navigationGroup = 'Emergency';

    protected string $view = 'filament.staff.pages.fix';

    protected static ?string $title = 'Report an issue';

    protected static ?int $navigationSort = 61;

    protected static string|BackedEnum|null $navigationIcon = 'myicon-s-urgent';

    public function getSubheading(): ?string
    {
        return __('Issue a fix ticket to OUs support group ie. IT, Facilities, etc.');
    }
}

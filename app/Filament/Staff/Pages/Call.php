<?php

declare(strict_types=1);

namespace App\Filament\Staff\Pages;

use BackedEnum;
use Filament\Pages\Page;
use UnitEnum;

class Call extends Page
{
    protected static string|UnitEnum|null $navigationGroup = 'Emergency';

    protected string $view = 'filament.staff.pages.call';

    protected static ?string $title = 'Contact someone';

    protected static ?int $navigationSort = 62;

    protected static string|BackedEnum|null $navigationIcon = 'myicon-s-phone-call';

    public function getSubheading(): ?string
    {
        return __('Contact number of emergency personnel ..... ERT, Fire, Ambulance, Security, etc.');
    }
}

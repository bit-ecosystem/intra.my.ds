<?php

declare(strict_types=1);

namespace App\Filament\Staff\Pages;

use App\Filament\Staff\Widgets;
use BackedEnum;
use Filament\Pages\Dashboard as BaseDashboard;
use UnitEnum;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Target';

    protected static string|UnitEnum|null $navigationGroup = 'To Do';

    protected static string|BackedEnum|null $navigationIcon = 'myicon-s-target';

    protected static ?int $navigationSort = 13;

    public function getSubheading(): ?string
    {
        return __('Target settings and progress overview for your work in ATM.');
    }

    public function getColumns(): int|array
    {
        return 4;
    }

    public function getWidgets(): array
    {
        // Only these widgets appear on the Dashboard
        return [
            Widgets\StaffInfo::class,
            Widgets\RolesWidgetMini::class,
        ];
    }
}

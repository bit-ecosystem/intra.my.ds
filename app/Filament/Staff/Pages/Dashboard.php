<?php

declare(strict_types=1);

namespace App\Filament\Staff\Pages;

use App\Filament\Staff\Widgets;
use BackedEnum;
use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Contracts\Support\Htmlable;
use UnitEnum;

class Dashboard extends BaseDashboard
{
    protected static string|BackedEnum|null $navigationIcon = 'myicon-s-target';

    protected static ?int $navigationSort = 13;

    public function getTitle(): string|Htmlable
    {
        return __('Target');
    }

    public static function getNavigationLabel(): string
    {
        return __('Target');
    }

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return __('To Do');
    }

    public function getSubheading(): ?string
    {
        return __('Target settings and progress overview for your work.');
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

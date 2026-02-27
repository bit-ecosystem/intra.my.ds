<?php

declare(strict_types=1);

namespace App\Filament\Staff\Pages;

use BackedEnum;
use Filament\Pages\Page;
use UnitEnum;

class Artifact extends Page
{
    protected static ?string $title = 'Assigned Assets';

    protected static string|UnitEnum|null $navigationGroup = 'Artifact';

    protected static string|BackedEnum|null $navigationIcon = 'myicon-asset-own';

    protected static ?int $navigationSort = 22;

    protected string $view = 'filament.staff.pages.artifact';

    protected function getHeaderWidgets(): array
    {
        return [
            // \App\Filament\Staff\Widgets\UserRolesWidget::class,
            // \App\Filament\Hrm\Resources\Staff\Widgets\ShiftMixByOrgUnitTable::class,
        ];
    }

    public function getSubheading(): ?string
    {
        return __('Asset/Equipment/Items issued to you or your support group.');
    }
}

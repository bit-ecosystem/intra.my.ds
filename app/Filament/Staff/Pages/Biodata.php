<?php

declare(strict_types=1);

namespace App\Filament\Staff\Pages;

use BackedEnum;
use Filament\Pages\Page;
use UnitEnum;

class Biodata extends Page
{
    protected static ?string $title = 'Profile';

    protected static string|UnitEnum|null $navigationGroup = 'Artifact';

    protected static string|BackedEnum|null $navigationIcon = 'myicon-id-staff';

    protected static ?int $navigationSort = 21;

    protected string $view = 'filament.staff.pages.biodata';

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Staff\Widgets\StaffInfo::class,
            \App\Filament\Staff\Widgets\RolesWidget::class,
        ];
    }
}

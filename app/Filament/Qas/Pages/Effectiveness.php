<?php

declare(strict_types=1);

namespace App\Filament\Qas\Pages;

use BackedEnum;
use Filament\Pages\Page;
use UnitEnum;

class Effectiveness extends Page
{
    protected static string|UnitEnum|null $navigationGroup = 'Improvement Actions';

    protected string $view = 'filament.qas.pages.quality';

    protected static ?string $title = 'Effectiveness';

    protected static ?int $navigationSort = 53;

    // protected static string|BackedEnum|null $navigationIcon = 'myicon-id-staff';

    protected function getHeaderWidgets(): array
    {
        return [
            // \App\Filament\Staff\Widgets\StaffInfo::class,
            // \App\Filament\Staff\Widgets\RolesWidget::class,
        ];
    }

    public function getSubheading(): ?string
    {
        return __('Effectiveness Management.');
    }
}

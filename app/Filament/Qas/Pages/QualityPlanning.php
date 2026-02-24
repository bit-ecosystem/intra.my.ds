<?php

declare(strict_types=1);

namespace App\Filament\Qas\Pages;

use Filament\Pages\Page;
use UnitEnum;

class QualityPlanning extends Page
{
    protected static string|UnitEnum|null $navigationGroup = 'Quality Planning';

    protected string $view = 'filament.qas.pages.quality';

    protected static ?string $title = 'Quality Planning';

    protected static ?int $navigationSort = 80;

    protected function getHeaderWidgets(): array
    {
        return [
            // \App\Filament\Staff\Widgets\StaffInfo::class,
            // \App\Filament\Staff\Widgets\RolesWidget::class,
        ];
    }

    public function getSubheading(): ?string
    {
        return __('Quality Planning Management.');
    }
}

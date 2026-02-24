<?php

declare(strict_types=1);

namespace App\Filament\Qas\Pages;

use Filament\Pages\Page;
use UnitEnum;

class SupplierQuality extends Page
{
    protected string $view = 'filament.qas.pages.quality';

    protected static ?string $title = 'Supplier Quality';

    protected static ?int $navigationSort = 70;

    protected static string|UnitEnum|null $navigationGroup = 'Supplier Quality';

    protected function getHeaderWidgets(): array
    {
        return [
            // \App\Filament\Staff\Widgets\StaffInfo::class,
            // \App\Filament\Staff\Widgets\RolesWidget::class,
        ];
    }

    public function getSubheading(): ?string
    {
        return __('Supplier Quality Management.');
    }
}

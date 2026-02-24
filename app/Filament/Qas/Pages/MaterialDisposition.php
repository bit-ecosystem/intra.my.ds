<?php

declare(strict_types=1);

namespace App\Filament\Qas\Pages;

use BackedEnum;
use Filament\Pages\Page;
use UnitEnum;

class MaterialDisposition extends Page
{
    protected static string|UnitEnum|null $navigationGroup = 'Product Control';

    protected string $view = 'filament.qas.pages.quality';

    protected static ?string $title = 'Material Dispositions';

    protected static ?int $navigationSort = 42;

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
        return __('Material Disposition Management.');
    }
}

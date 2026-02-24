<?php

declare(strict_types=1);

namespace App\Filament\Qas\Pages;

use BackedEnum;
use Filament\Pages\Page;
use UnitEnum;

class IncomingInspection extends Page
{
    protected static string|UnitEnum|null $navigationGroup = 'Quality Control';

    protected string $view = 'filament.qas.pages.quality';

    protected static ?string $title = 'Incoming Inspections';

    protected static ?int $navigationSort = 31;

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
        return __('Incoming Inspection Management.');
    }
}

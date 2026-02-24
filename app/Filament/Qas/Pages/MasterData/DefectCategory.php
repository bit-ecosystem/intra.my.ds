<?php

declare(strict_types=1);

namespace App\Filament\Qas\Pages\MasterData;

use Filament\Pages\Page;
use UnitEnum;

class DefectCategory extends Page
{
    protected string $view = 'filament.qas.pages.quality';

    protected static ?string $title = 'Defect Categories';

    protected static ?int $navigationSort = 110;

    protected static string|UnitEnum|null $navigationGroup = 'Master Data';

    protected function getHeaderWidgets(): array
    {
        return [
            // \App\Filament\Staff\Widgets\StaffInfo::class,
            // \App\Filament\Staff\Widgets\RolesWidget::class,
        ];
    }

    public function getSubheading(): ?string
    {
        return 'Defect Categories,
Severity Levels,
Disposition Types,
Root Cause Codes,
Test Methods,
Inspection Characteristics,

This keeps your system clean and consistent.';
    }
}

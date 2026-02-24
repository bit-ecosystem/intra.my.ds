<?php

declare(strict_types=1);

namespace App\Filament\Staff\Pages;

use BackedEnum;
use Filament\Pages\Page;
use UnitEnum;

class Workflow extends Page
{
    protected string $view = 'filament.staff.pages.workflow';

    protected static ?string $title = 'Request for something';

    protected static string|UnitEnum|null $navigationGroup = 'Catalog';

    protected static string|BackedEnum|null $navigationIcon = 'myicon-w-request';

    protected static ?int $navigationSort = 32;

    public function getSubheading(): ?string
    {
        return __('Request for support, service, item, asset, equipment, etc. through workflow system.');
    }

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Staff\Widgets\RequestWidget::class,
        ];
    }
}

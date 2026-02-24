<?php

declare(strict_types=1);

namespace App\Filament\Staff\Pages;

use BackedEnum;
use Filament\Pages\Page;
use UnitEnum;

class Task extends Page
{
    protected string $view = 'filament.staff.pages.task';

    protected static string|UnitEnum|null $navigationGroup = 'To Do';

    protected static string|BackedEnum|null $navigationIcon = 'myicon-task';

    protected static ?int $navigationSort = 11;

    public function getSubheading(): ?string
    {
        return __('Task active assignments and to do items, for yourself or your support group.');
    }

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Staff\Widgets\TaskWidget::class,
        ];
    }
}

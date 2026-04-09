<?php

declare(strict_types=1);

namespace App\Filament\Staff\Pages;

use App\Filament\Staff\Widgets;
use BackedEnum;
use Bites\Service\Concerns\HasHelp;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use UnitEnum;

class Task extends Page
{
    use HasHelp;

    protected static string|BackedEnum|null $navigationIcon = 'myicon-task';

    protected static ?int $navigationSort = 11;

    public function getTitle(): string|Htmlable
    {
        return __('Task');
    }

    public static function getNavigationLabel(): string
    {
        return __('Task');
    }

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return __('To Do');
    }

    public function getSubheading(): ?string
    {
        return __('Task active assignments and to do items, for yourself or your support group.');
    }

    protected string $view = 'filament.staff.pages.task';

    protected function getHeaderWidgets(): array
    {
        return [
            Widgets\ExpiringCert::class,
            Widgets\ReminderWidget::class,
            Widgets\TaskWidget::class,
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\Filament\Staff\Pages;

use BackedEnum;
use Filament\Pages\Page;
use UnitEnum;
use Illuminate\Contracts\Support\Htmlable;

class Task extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'myicon-task';

    protected static ?int $navigationSort = 11;
    public function getTitle(): string | Htmlable
    { return __('Task'); }
    public static function getNavigationLabel(): string
    { return __('Task'); }
    public static function getNavigationGroup(): string | UnitEnum | null
    { return __('To Do'); }
    public function getSubheading(): ?string
    { return __('Task active assignments and to do items, for yourself or your support group.'); }
    
    protected string $view = 'filament.staff.pages.task';

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Staff\Widgets\TaskWidget::class,
        ];
    }
}

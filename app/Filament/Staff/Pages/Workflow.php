<?php

declare(strict_types=1);

namespace App\Filament\Staff\Pages;

use App\Filament\Staff\Widgets\RequestWidget;
use BackedEnum;
use Bites\Shared\Concerns\HasHelp;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use UnitEnum;

class Workflow extends Page
{
    use HasHelp;

    protected static string|BackedEnum|null $navigationIcon = 'myicon-w-request';

    protected static ?int $navigationSort = 32;

    public function getTitle(): string|Htmlable
    {
        return __('Request for something');
    }

    public static function getNavigationLabel(): string
    {
        return __('Request for something');
    }

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return __('Catalog');
    }

    public function getSubheading(): ?string
    {
        return __('Request for support, service, item, asset, equipment, etc. through workflow system.');
    }

    protected string $view = 'filament.staff.pages.workflow';

    protected function getHeaderWidgets(): array
    {
        return [
            RequestWidget::class,
        ];
    }
}

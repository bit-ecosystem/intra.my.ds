<?php

declare(strict_types=1);

namespace App\Filament\Staff\Pages;

use BackedEnum;
use Filament\Pages\Page;
use UnitEnum;
use Illuminate\Contracts\Support\Htmlable;

class Artifact extends Page
{
    // title is translated via lang file
    protected static ?string $title = null;

    // group is translated via accessor below
    protected static string|UnitEnum|null $navigationGroup = null;

    protected static string|BackedEnum|null $navigationIcon = 'myicon-asset-own';

    protected static ?int $navigationSort = 22;

    protected string $view = 'filament.staff.pages.artifact';

    protected function getHeaderWidgets(): array
    {
        return [
            // \App\Filament\Staff\Widgets\UserRolesWidget::class,
            // \App\Filament\Hrm\Resources\Staff\Widgets\ShiftMixByOrgUnitTable::class,
        ];
    }
    public function getTitle(): string | Htmlable
    {
        return __('bites::resources.artifact.title');
    }
    public static function getNavigationLabel(): string
    {
        return __('bites::resources.artifact.title');
    }

    public static function getNavigationGroup(): string | UnitEnum | null
    {
        return __('bites::resources.artifact.navigation_group');
    }

    public function getSubheading(): ?string
    {
        return __('bites::resources.artifact.subheading');
    }
}

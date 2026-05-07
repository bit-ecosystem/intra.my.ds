<?php

declare(strict_types=1);

namespace Bites\Platform\UI\Http\UI\Staff\Pages;

use BackedEnum;
use Bites\Service\Concerns\HasHelp;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use UnitEnum;

class Artifact extends Page
{
    // use HasHelp;

    protected static string|BackedEnum|null $navigationIcon = 'myicon-asset-own';

    protected static ?int $navigationSort = 22;

    public function getTitle(): string|Htmlable
    {
        return __('Assigned Assets');
    }

    public static function getNavigationLabel(): string
    {
        return __('Assigned Assets');
    }

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return __('Artifact');
    }

    public function getSubheading(): ?string
    {
        return __('Asset/Equipment/Items issued to you or your support group.');
    }

    protected string $view = 'filament.staff.pages.artifact';

    protected function getHeaderWidgets(): array
    {
        return [
            // \App\Filament\Staff\Widgets\UserRolesWidget::class,
            // \App\Filament\Hrm\Resources\Staff\Widgets\ShiftMixByOrgUnitTable::class,
        ];
    }

    public static function myclass(): string
    {
        // Late static binding: resolves to the calling class
        return static::class;          // e.g., App\Models\User
    }
}

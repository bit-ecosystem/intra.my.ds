<?php

declare(strict_types=1);

namespace App\Filament\Staff\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use UnitEnum;

class Biodata extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'myicon-id-staff';

    protected static ?int $navigationSort = 21;

    public function getTitle(): string|Htmlable
    {
        return __('Profile');
    }

    public static function getNavigationLabel(): string
    {
        return __('Profile');
    }

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return __('Artifact');
    }

    public function getSubheading(): ?string
    {
        return __('Your profile, roles and qualifications.');
    }

    protected string $view = 'filament.staff.pages.biodata';

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Staff\Widgets\StaffInfo::class,
            \App\Filament\Staff\Widgets\RolesWidget::class,
        ];
    }
}

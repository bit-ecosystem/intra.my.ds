<?php

declare(strict_types=1);

namespace App\Filament\Staff\Pages;

use BackedEnum;
use Filament\Pages\Page;
use UnitEnum;
use Illuminate\Contracts\Support\Htmlable;

class Fix extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'myicon-s-urgent';

    protected static ?int $navigationSort = 61;
    public function getTitle(): string | Htmlable
    { return __('Report an issue'); }
    public static function getNavigationLabel(): string
    { return __('Report an issue'); }
    public static function getNavigationGroup(): string | UnitEnum | null
    { return __('Emergency'); }
    public function getSubheading(): ?string
    { return __('Issue a fix ticket to OUs support group ie. IT, Facilities, etc.'); }
    
    protected string $view = 'filament.staff.pages.fix';
}

<?php

declare(strict_types=1);

namespace App\Enums;

use BackedEnum;
use Filament\Support\Colors\Color;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

enum ProcessStages: string
{
    // Tier 1: Operations
    case APPROVING = 'Approving';
    case VERIFYING = 'Verifying';
    case EXECUTING = 'Executing';
    case DOCUMENTING = 'Documenting';
    case SUPERVISING = 'Supervising';

    public function dbValue(): string
    {
        return match ($this) {
            self::APPROVING => 'Approving',
            self::VERIFYING => 'Verifying',
            self::EXECUTING => 'Executing',
            self::DOCUMENTING => 'Documenting',
            self::SUPERVISING => 'Supervising',
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::APPROVING => __('Approving'),
            self::VERIFYING => __('Verifying'),
            self::EXECUTING => __('Executing'),
            self::DOCUMENTING => __('Documenting'),
            self::SUPERVISING => __('Supervising'),
        };
    }

    /**
     * Display label for Filament (tabs, badges, selects).
     */
    public function getLabel(): string|Htmlable|null
    {
        // If you ever want to return rich HTML, return new HtmlString('<strong>...</strong>')
        return $this->value;
    }

    /**
     * Icon for Filament components.
     * Return a Heroicon name (string), a BackedEnum (if you map to an icon enum), Htmlable, or null.
     *
     * Common pattern: return Heroicons (outline variant). Examples:
     * - 'heroicon-o-shield-check'
     * - 'heroicon-o-clipboard-document-check'
     */
    public function getIcon(): string|BackedEnum|Htmlable|null
    {
        return match ($this) {
            // Operations
            self::APPROVING => 'heroicon-o-shield-check',
            self::VERIFYING => 'heroicon-o-clipboard-document-check',
            self::EXECUTING => 'heroicon-o-star',
            self::DOCUMENTING => 'bites-l-technical',
            self::SUPERVISING => 'heroicon-o-user-circle',
        };
    }

    /**
     * Color used by Filament badges / tags / icons.
     * Can be a single color or an array ['from' => ..., 'to' => ...] if you prefer gradients.
     *
     * Filament built-ins include: primary, secondary, success, warning, danger,
     * info, gray, slate, zinc, neutral, stone, amber, indigo, purple, pink, rose,
     * emerald, teal, cyan, sky, blue, violet, lime, fuchsia, etc.
     */
    public function getColor(): string|array|null
    {
        return match ($this) {
            // Operations
            self::APPROVING => Color::Rose,
            self::VERIFYING => Color::Amber,
            self::EXECUTING => Color::Pink,
            self::DOCUMENTING => Color::Orange,
            self::SUPERVISING => Color::Slate,

        };
    }

    /**
     * A short description for tooltips, cards, or helper text.
     */
    public function getDescription(): string|Htmlable|null
    {
        return match ($this) {
            // Operations
            self::APPROVING => 'Approving : Review and approval processes.',
            self::VERIFYING => 'Verifying : Validation and verification activities.',
            self::EXECUTING => 'Executing : Implementation and execution of tasks.',
            self::DOCUMENTING => 'Documenting : Documentation and record-keeping.',
            self::SUPERVISING => 'Supervising : Oversight and supervision activities.',
        };
    }

    /**
     * Optional: name-value list for a Select component.
     */
    public static function options(): array
    {
        $out = [];
        foreach (self::cases() as $case) {
            $out[$case->value] = $case->getLabel();
        }

        return $out;
    }

    /**
     * Optional: map of [value => ['label' => ..., 'icon' => ..., 'color' => ..., 'description' => ...]]
     * Handy for resource tables or custom UI widgets.
     */
    public static function meta(): array
    {
        $meta = [];
        foreach (self::cases() as $case) {
            $meta[$case->value] = [
                'label' => $case->getLabel(),
                'icon' => $case->getIcon(),
                'color' => $case->getColor(),
                'description' => $case->getDescription(),
            ];
        }

        return $meta;
    }
}

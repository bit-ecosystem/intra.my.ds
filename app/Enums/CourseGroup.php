<?php

declare(strict_types=1);

namespace App\Enums;

use BackedEnum;
use Filament\Support\Colors\Color;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

enum CourseGroup: string
{
    // Tier 1: Operations
    case SAFETY = 'Safety';
    case COMPLIANCE = 'Compliance';
    case QUALITY = 'Quality';
    case TECHNICAL = 'Technical';

    // Tier 2: Production (ISA-95 context)
    case PRODUCT = 'Product';
    case PROCESS = 'Process';
    case EQUIPMENT = 'Equipment';

    // Tier 3: Growth
    case EFFICIENCY = 'Efficiency';
    case DIGITAL = 'Digital';
    case SOFT_SKILLS = 'Soft Skills';
    case LEADERSHIP = 'Leadership';
    case ONBOARDING = 'Onboarding';

    public function dbValue(): string
    {
        return match ($this) {
            self::SAFETY => 'Safety',
            self::COMPLIANCE => 'Compliance',
            self::QUALITY => 'Quality',
            self::TECHNICAL => 'Technical',
            self::PRODUCT => 'Product',
            self::PROCESS => 'Process',
            self::EQUIPMENT => 'Equipment',
            self::EFFICIENCY => 'Efficiency',
            self::DIGITAL => 'Digital',
            self::SOFT_SKILLS => 'Soft Skills',
            self::LEADERSHIP => 'Leadership',
            self::ONBOARDING => 'Onboarding',
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::SAFETY => __('Safety'),
            self::COMPLIANCE => __('Compliance'),
            self::QUALITY => __('Quality'),
            self::TECHNICAL => __('Technical'),
            self::PRODUCT => __('Product'),
            self::PROCESS => __('Process'),
            self::EQUIPMENT => __('Equipment'),
            self::EFFICIENCY => __('Efficiency'),
            self::DIGITAL => __('Digital'),
            self::SOFT_SKILLS => __('Soft Skills'),
            self::LEADERSHIP => __('Leadership'),
            self::ONBOARDING => __('Onboarding'),
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
            self::SAFETY => 'heroicon-o-shield-check',
            self::COMPLIANCE => 'heroicon-o-clipboard-document-check',
            self::QUALITY => 'heroicon-o-star',
            self::TECHNICAL => 'bites-l-technical',

            // Production
            self::PRODUCT => 'heroicon-o-cube',
            self::PROCESS => 'bites-l-process',
            self::EQUIPMENT => 'heroicon-o-wrench-screwdriver',

            // Growth
            self::EFFICIENCY => 'heroicon-o-bolt',
            self::DIGITAL => 'heroicon-o-code-bracket',
            self::SOFT_SKILLS => 'heroicon-o-chat-bubble-left-ellipsis',
            self::LEADERSHIP => 'heroicon-o-flag',
            self::ONBOARDING => 'heroicon-o-cursor-arrow-ripple',
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
            self::SAFETY => Color::Rose,
            self::COMPLIANCE => Color::Amber,
            self::QUALITY => Color::Pink,
            self::TECHNICAL => Color::Orange,

            // Production
            self::PRODUCT => Color::Blue,
            self::PROCESS => Color::Sky,
            self::EQUIPMENT => Color::Violet,

            // Growth
            self::EFFICIENCY => Color::Green,
            self::DIGITAL => Color::Lime,
            self::SOFT_SKILLS => Color::Emerald,
            self::LEADERSHIP => Color::Cyan,

            // Newbie
            self::ONBOARDING => Color::Mist,
        };
    }

    /**
     * A short description for tooltips, cards, or helper text.
     */
    public function getDescription(): string|Htmlable|null
    {
        return match ($this) {
            // Operations
            self::SAFETY => 'Safety : Workplace HSE, LOTO, PPE, and emergency readiness.',
            self::COMPLIANCE => 'Compliance : Policies, legal, anti-bribery, and data governance.',
            self::QUALITY => 'Quality : QC methods, SPC, calibration, and non-conformance.',
            self::TECHNICAL => 'Technical : SOPs, machine setup, maintenance, and blueprints.',

            // Production
            self::PRODUCT => 'Product : Product/material-focused competencies and standards.',
            self::PROCESS => 'Process : Process segments and manufacturing workflows.',
            self::EQUIPMENT => 'Equipment : Equipment classes, operation, and maintenance topics.',

            // Growth
            self::EFFICIENCY => 'Efficiency : Lean, 5S, value-streams, and continuous improvement.',
            self::DIGITAL => 'Digital/IT : MES/ERP usage, cybersecurity, and digital tools.',
            self::SOFT_SKILLS => 'Soft Skills : Communication, teamwork, and collaboration.',
            self::LEADERSHIP => 'Leadership : Coaching, delegation, and frontline leadership.',
            self::ONBOARDING => 'Onboarding : Company intro, site security, and handbook.',
        };
    }

    /**
     * Tier grouping for tabs/filters in Filament.
     */
    public function getTier(): string
    {
        return match ($this) {
            self::SAFETY, self::COMPLIANCE, self::QUALITY, self::TECHNICAL => 'Operations',

            self::PRODUCT, self::PROCESS, self::EQUIPMENT => 'Production',

            self::EFFICIENCY, self::DIGITAL, self::SOFT_SKILLS, self::LEADERSHIP, self::ONBOARDING => 'Growth',
        };
    }

    /**
     * Whether this group represents an ISA‑95 scope dimension.
     */
    public function isScope(): bool
    {
        return in_array($this, [self::PRODUCT, self::PROCESS, self::EQUIPMENT], true);
    }

    /**
     * Get all cases for a specific tier (useful for Filament Tabs).
     */
    public static function forTier(string $tier): array
    {
        return array_values(array_filter(
            self::cases(),
            fn (self $case): bool => $case->getTier() === $tier
        ));
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
                'tier' => $case->getTier(),
                'is_scope' => $case->isScope(),
            ];
        }

        return $meta;
    }
}

<?php

declare(strict_types=1);

namespace App\Filament\Lms\Resources\Courses\Widgets;

use App\Enums\CourseGroup;
use App\Filament\Lms\Resources\Courses\CourseResource;
use Bites\Kbm\Lms\Models\Course;
use Filament\Facades\Filament;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Cache;

class CourseWidget extends Widget
{
    protected string $view = 'filament.lms.resources.courses.widgets.course-widget';

    // Make the widget span full width when used as a header widget.
    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 10;

    public static function canView(): bool
    {
        return true;
    }

    /**
     * EXACT API your Blade calls.
     * Returns an array of "categories" (cards), each with a list of "features".
     */
    protected function getCategories(): array
    {
        // Cache briefly to reduce queries
        return Cache::remember('course_widget.categories', 60, function () {
            // Build a quick count per CourseGroup value
            $counts = Course::query()
                ->select('category')
                ->get()
                ->groupBy('category')
                ->map->count()
                ->all();

            // Base URL to Courses resource index (adjust slug if your resource path differs)
            // Example assumes the resource index is: /admin/resources/courses
            // $panel = Filament::getCurrentPanel();
            // $indexUrl = $panel->getUrl(null); // <-- change 'courses' if your slug differs
            $indexUrl = CourseResource::getUrl('index');
            // Helper to build a filter link (uses Filament Table filter named "category")
            $filterUrl = function (string $groupValue) use ($indexUrl): string {
                return $indexUrl.'?filters[category][value]='.urlencode($groupValue);
            };

            // Map a tier to a top-level "category" card with color & icon (must match the Blade’s $colors keys)
            $tierMeta = [
                'Operations' => ['icon' => 'heroicon-o-cog-6-tooth', 'color' => 'blue'],
                'Production' => ['icon' => 'heroicon-o-cube',        'color' => 'violet'],
                'Growth' => ['icon' => 'heroicon-o-bolt',        'color' => 'emerald'],
            ];

            $categories = [];

            foreach (['Operations', 'Production', 'Growth'] as $tierName) {
                $groups = CourseGroup::forTier($tierName); // array of enum cases
                $features = [];

                foreach ($groups as $group) {
                    $value = $group->value;
                    $features[] = [
                        'name' => $group->getLabel(),                      // e.g. "Safety"
                        'url' => $filterUrl($value),                      // click to open index filtered by category
                        'resource' => ($counts[$value] ?? 0).' '.str('course')->plural($counts[$value] ?? 0),
                        'description' => $group->getDescription(),
                    ];
                }

                $categories[] = [
                    'name' => $tierName,
                    'icon' => $tierMeta[$tierName]['icon'],
                    'color' => $tierMeta[$tierName]['color'], // must be one of the blade's palette keys
                    'features' => $features,
                ];
            }

            return $categories;
        });
    }

    /**
     * Your Blade uses `$this->getCategories()` directly,
     * but we can also pass it via getViewData() if you prefer.
     */
    protected function getViewData(): array
    {
        return [
            'categories' => $this->getCategories(),
        ];
    }
}

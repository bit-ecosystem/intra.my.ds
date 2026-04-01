<?php

declare(strict_types=1);

namespace App\Filament\Lms\Resources\Courses\Pages;

use App\Enums\CourseGroup;
use App\Filament\Lms\Resources\Courses\CourseResource;
use Bites\Kbm\Lms\Models\Course;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListCourses extends ListRecords
{
    protected int|string|array $columnSpan = 'full';

    protected static string $resource = CourseResource::class;

    public function getSubheading(): ?string
    {
        return __('Courses, modules, quizzes and learning materials for staff.');
    }

    protected function getHeaderWidgets(): array
    {
        return [
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getDefaultActiveTab(): string|int|null
    {
        $onboardingCount = Course::query()
            ->where('status', 'published')
            ->where('category', 'Onboarding')
            ->count();

        return $onboardingCount > 0 ? 'Onboarding' : 'all';
    }

    public function getTabs(): array
    {
        $counts = Course::query()
            ->where('status', 'published')
            ->selectRaw('category, COUNT(*) as total')
            ->groupBy('category')
            ->pluck('total', 'category');

        $totalPublished = (int) $counts->sum();
        $tabs = [];

        // "All" tab - use a simple string key 'all'
        $tabs['all'] = Tab::make(__('All'))
            ->badge($totalPublished)
            ->badgeColor('primary')
            ->icon('heroicon-o-rectangle-stack')
            // Explicitly return the query
            ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'published'));

        foreach (CourseGroup::meta() as $key => $meta) {
            $tabs[$key] = Tab::make($meta['label'])
                ->badge(
                    fn () => Course::query()
                        ->where('status', 'published')
                        ->where('category', $key) // Uses "Safety", "Compliance", etc.
                        ->count()
                )
                ->badgeColor($meta['color'])
                ->icon($meta['icon'])
                //    ->color($meta['color'])
                ->modifyQueryUsing(
                    fn (Builder $query) => $query
                        ->where('status', 'published')
                        ->where('category', $key)
                );
        }

        $uncategorizedCount = (int) ($counts[null] ?? 0);
        if ($uncategorizedCount > 0) {
            $tabs['uncategorized'] = Tab::make(__('Uncategorized'))
                ->icon('heroicon-o-tag')
                ->badge($uncategorizedCount)
                ->badgeColor('gray')
                ->modifyQueryUsing(fn (Builder $q) => $q->where('status', 'published')->whereNull('category'));
        }

        return $tabs;
    }
}

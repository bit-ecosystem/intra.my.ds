<?php

declare(strict_types=1);

namespace Bites\Business\Lms\Http\UI\Staff\Resources\Courses\Pages;

use App\Enums\CourseGroup;
use Bites\Business\Lms\Entities\Course;
use Bites\Business\Lms\Entities\Module;
use Bites\Business\Lms\Http\UI\Staff\Resources\Courses\CourseResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Enums\IconPosition;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

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
        return [];
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
            ->visibleTo(Auth::user())
            ->count();

        return $onboardingCount > 0 ? 'Onboarding' : 'all';
    }

    public function getTabs(): array
    {
        $counts = Course::query()
            ->where('status', 'published')
            ->selectRaw('category, COUNT(*) as total')
            ->groupBy('category')
            ->visibleTo(Auth::user())
            ->pluck('total', 'category');

        $totalPublished = (int) $counts->sum();
        $tabs = [];

        // "All" tab - use a simple string key 'all'
        $tabs['all'] = Tab::make(__('All'))
            // ->badge($totalPublished)
            // ->badgeColor('primary')
            ->icon('heroicon-o-rectangle-stack')
            // Explicitly return the query
            ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'published'));

        foreach (CourseGroup::meta() as $key => $meta) {
            $tabs[$key] = Tab::make('')
                ->extraAttributes([
                    'x-tooltip.raw' => $meta['description'],
                ])
                ->badge(function () use ($key) {
                    $count = Module::query()
                        ->whereHas('courses', function ($query) use ($key) {
                            $query->where('status', 'published')
                                ->where('category', $key);
                        })
                        ->count();

                    return $count > 0 ? $count : null;
                })
                ->badgeColor($meta['color'])
                ->icon($meta['icon'])
                ->IconPosition(IconPosition::After)
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

<?php

declare(strict_types=1);

namespace App\Filament\Lms\Resources\Courses\Pages;

use App\Filament\Lms\Resources\Courses\CourseResource;
use App\Models\Lms\Course;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;

class ListCourses extends ListRecords
{
    protected static string $resource = CourseResource::class;

    public function getSubheading(): ?string
    {
        return __('Courses, modules, quizzes and learning materials for staff.');
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        // Collect unique categories (including null to represent "Uncategorized")
        $categories = Course::query()
            ->select('category')
            ->distinct()
            ->pluck('category')
            ->toArray();

        $tabs = [];

        // "All" tab: no filter, counts all
        $tabs['all'] = Tab::make('All')
            ->badge(Course::count())
            ->modifyQueryUsing(fn ($query) => $query);

        // For each category, add a tab. Handle nulls as "Uncategorized".
        foreach ($categories as $category) {
            $label = $category ?: 'Uncategorized';

            $tabs[$label] = Tab::make($label)
                ->badge(
                    Course::query()->when(
                        $category,
                        fn ($q) => $q->where('category', $category),
                        fn ($q) => $q->whereNull('category')
                    )
                        ->count()
                )
                ->modifyQueryUsing(function ($query) use ($category) {
                    return $query->when(
                        $category,
                        fn ($q) => $q->where('category', $category),
                        fn ($q) => $q->whereNull('category')
                    );
                });
        }

        return $tabs;
    }
}

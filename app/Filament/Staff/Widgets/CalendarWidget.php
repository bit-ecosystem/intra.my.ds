<?php

declare(strict_types=1);

namespace App\Filament\Staff\Widgets;

use Filament\Widgets\Widget;

class CalendarWidget extends Widget
{
    protected static ?string $heading = 'Calendar';

    // This points to the Blade file you’ll create next
    protected string $view = 'filament.staff.widgets.calendar-widget';

    protected int|string|array $columnSpan = 'full'; // optional: full width

    // If you need to pass events from PHP to JS, expose them here:
    protected function getViewData(): array
    {
        $eventDay = now()->toDateString();

        return [
            'events' => [
                [
                    'title' => 'Learn JavaScript',
                    'start' => $eventDay,
                    'classNames' => ['fc-event-primary'],
                ],
            ],
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\Filament\Staff\Widgets;

use App\Models\Event;
use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;

class TestCal extends Widget
{
    protected string $view = 'filament.staff.widgets.test-cal';

    // protected int|string|array $columnSpan = 'full';

    // Controls
    public ?string $activeMonth = null; // anchor month (YYYY-mm-01)

    public array $weeks = [];           // weeks[weekIndex][0..6] => day array

    public array $itemsByDate = [];     // 'Y-m-d' => [ ...items ]

    public string $splitMode = 'am_pm'; // how to split into 2 columns (am_pm|type|none)

    public function mount(): void
    {
        $this->activeMonth ??= now()->startOfMonth()->toDateString();
        $this->buildWeeks();
        $this->loadItems();
    }

    public function previousWeek(): void
    {
        $first = $this->visibleStart();
        $this->activeMonth = $first->copy()->subWeek()->startOfMonth()->toDateString();
        $this->buildWeeks(from: $first->copy()->subWeek(), weeks: 1);
        $this->loadItems(rangeOverride: [$first->copy()->subWeek(), $first->copy()->subWeek()->copy()->endOfWeek(Carbon::SUNDAY)]);
    }

    public function nextWeek(): void
    {
        $first = $this->visibleStart();
        $this->activeMonth = $first->copy()->addWeek()->startOfMonth()->toDateString();
        $this->buildWeeks(from: $first->copy()->addWeek(), weeks: 1);
        $this->loadItems(rangeOverride: [$first->copy()->addWeek(), $first->copy()->addWeek()->copy()->endOfWeek(Carbon::SUNDAY)]);
    }

    public function previousMonth(): void
    {
        $this->activeMonth = Carbon::parse($this->activeMonth)->subMonthNoOverflow()->startOfMonth()->toDateString();
        $this->buildWeeks();
        $this->loadItems();
    }

    public function nextMonth(): void
    {
        $this->activeMonth = Carbon::parse($this->activeMonth)->addMonthNoOverflow()->startOfMonth()->toDateString();
        $this->buildWeeks();
        $this->loadItems();
    }

    public function goToday(): void
    {
        $this->activeMonth = now()->startOfMonth()->toDateString();
        $this->buildWeeks();
        $this->loadItems();
    }

    protected function visibleStart(): Carbon
    {
        $month = Carbon::parse($this->activeMonth);

        return $month->copy()->startOfMonth()->startOfWeek(Carbon::MONDAY);
    }

    protected function visibleEnd(): Carbon
    {
        $month = Carbon::parse($this->activeMonth);

        return $month->copy()->endOfMonth()->endOfWeek(Carbon::SUNDAY);
    }

    /**
     * Build a matrix: weeks -> 7 days.
     *
     * @param  Carbon|null  $from  If provided, start from that week's Monday (or Sunday if you prefer)
     * @param  int  $weeks  How many weeks to build; default covers the whole month range.
     */
    protected function buildWeeks(?Carbon $from = null, int $weeks = 0): void
    {
        $start = $from instanceof Carbon ? $from->copy()->startOfWeek(Carbon::MONDAY) : $this->visibleStart();
        $end = $from && $weeks > 0
            ? $start->copy()->addWeeks($weeks - 1)->endOfWeek(Carbon::SUNDAY)
            : $this->visibleEnd();

        $cursor = $start->copy();
        $weeksArr = [];
        $row = [];

        while ($cursor->lte($end)) {
            $row[] = [
                'date' => $cursor->toDateString(),
                'isCurrentMonth' => $cursor->isSameMonth(Carbon::parse($this->activeMonth)),
                'isToday' => $cursor->isToday(),
                'day' => $cursor->day,
                'weekdayIso' => $cursor->dayOfWeekIso, // 1..7
            ];

            if (count($row) === 7) {
                $weeksArr[] = $row;
                $row = [];
            }

            $cursor->addDay();
        }

        if ($row !== []) {
            // Should not happen if endOfWeek used, but keep it safe.
            $weeksArr[] = $row;
        }

        $this->weeks = $weeksArr;
    }

    /**
     * Load items grouped by date. Replace with your model & logic.
     * Use rangeOverride to optimize for single-week nav.
     */
    protected function loadItems(?array $rangeOverride = null): void
    {
        [$start, $end] = $rangeOverride
            ? $rangeOverride
            : [$this->visibleStart(), $this->visibleEnd()];

        // Example schema: Event with start_date (and optional end_date)
        $events = Event::query()
            ->whereDate('end_date', '>=', $start->toDateString())
            ->whereDate('start_date', '<=', $end->toDateString())
            ->orderBy('start_date')
            ->get(['id', 'title', 'start_date', 'end_date', 'type', 'status', 'color']);

        $grouped = [];

        foreach ($events as $event) {
            $cStart = Carbon::parse($event->start_date)->max($start);
            $cEnd = Carbon::parse($event->end_date ?? $event->start_date)->min($end);

            $cursor = $cStart->copy();
            while ($cursor->lte($cEnd)) {
                $key = $cursor->toDateString();
                $grouped[$key][] = [
                    'id' => $event->id,
                    'title' => $event->title,
                    'type' => $event->type ?? null,   // for split-by-type
                    'status' => $event->status ?? null,
                    'color' => $event->color ?? null,
                    'start' => $event->start_date,
                    'end' => $event->end_date,
                ];
                $cursor->addDay();
            }
        }

        $this->itemsByDate = $grouped;
    }

    public function onDateClick(string $date): void
    {
        // Open modal / navigate / emit
        // redirect()->route('filament.admin.resources.events.create', ['date' => $date]);
    }

    /**
     * Helper that splits items into two buckets for a day cell.
     * Adjust criteria per your needs (AM/PM, type, etc.).
     */
    public function splitItemsForDay(string $date): array
    {
        $items = $this->itemsByDate[$date] ?? [];

        return match ($this->splitMode) {
            'am_pm' => [
                'left' => array_values(array_filter($items, fn (array $i): bool => $this->startsMorning($i['start']))),
                'right' => array_values(array_filter($items, fn (array $i): bool => ! $this->startsMorning($i['start']))),
            ],
            'type' => [
                'left' => array_values(array_filter($items, fn (array $i): bool => in_array($i['type'], ['work', 'meeting', 'ops']))),
                'right' => array_values(array_filter($items, fn (array $i): bool => ! in_array($i['type'], ['work', 'meeting', 'ops']))),
            ],
            default => [
                // Fallback: split evenly
                'left' => array_values(array_filter($items, fn ($_, $idx): bool => $idx % 2 === 0, ARRAY_FILTER_USE_BOTH)),
                'right' => array_values(array_filter($items, fn ($_, $idx): bool => $idx % 2 === 1, ARRAY_FILTER_USE_BOTH)),
            ],
        };
    }

    protected function startsMorning(?string $startDateTime): bool
    {
        if (! $startDateTime) {
            return true;
        }

        $t = Carbon::parse($startDateTime);

        return (int) $t->format('H') < 12;
    }
}

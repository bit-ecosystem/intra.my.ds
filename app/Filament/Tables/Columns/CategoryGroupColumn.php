<?php

namespace App\Filament\Tables\Columns;

use App\Filament\Lms\Resources\Courses\CourseResource; // ← adjust if your resource class differs
use App\Models\Lms\Course;
use Filament\Support\Components\Contracts\HasEmbeddedView;
use Filament\Tables\Columns\Column;

class CategoryGroupColumn extends Column implements HasEmbeddedView
{
    protected int $limit = 8;

    /** @var 'view'|'edit' */
    protected string $linkAction = 'view';

    /** @var class-string|null */
    protected ?string $resourceClass = CourseResource::class;

    /** @var bool Exclude the current row from the sibling list */
    protected bool $excludeCurrent = false;

    /**
     * Cache siblings keyed by category string.
     * @var array<string, \Illuminate\Support\Collection<int, \App\Models\LCourse>>
     */
    protected static array $byCategoryCache = [];

    /**
     * Tailwind color classes for the dot; we pick a stable color per category.
     * @var string[]
     */
    protected array $dotPalette = [
        'bg-primary-500', 'bg-amber-500', 'bg-emerald-500',
        'bg-sky-500', 'bg-rose-500', 'bg-violet-500', 'bg-fuchsia-500',
    ];

    /** Public API — configure from your table() definition */
    public function limit(int $limit): static
    {
        $this->limit = $limit;
        return $this;
    }

    /** 'view' or 'edit' */
    public function linkAction(string $action): static
    {
        $this->linkAction = in_array($action, ['view', 'edit'], true) ? $action : 'view';
        return $this;
    }

    /** Set a different resource class if needed */
    public function resource(string $resourceClass): static
    {
        $this->resourceClass = $resourceClass;
        return $this;
    }

    /** Exclude the current row from the rendered list */
    public function excludeCurrent(bool $exclude = true): static
    {
        $this->excludeCurrent = $exclude;
        return $this;
    }

    public function toEmbeddedHtml(): string
    {
        $record = $this->getRecord(); // current LCourse row
        if (! $record) {
            return '';
        }

        $category = (string) $record->category;

        // Build a per-page cache for all categories present on the current page (if possible).
        if (empty(static::$byCategoryCache)) {
            $livewire = $this->getLivewire();
            $tableRecords = method_exists($livewire, 'getTableRecords')
                ? $livewire->getTableRecords()
                : null;

            if ($tableRecords && $tableRecords->isNotEmpty()) {
                $categories = $tableRecords
                    ->pluck('category')
                    ->filter(fn ($c) => filled($c))
                    ->map(fn ($c) => (string) $c)
                    ->unique()
                    ->values();

                if ($categories->isNotEmpty()) {
                    $all = Course::query()
                        ->whereIn('category', $categories)
                        ->orderBy('title')
                        ->get(['id', 'title', 'category', 'description', 'code', 'status'])
                        ->groupBy('category');

                    foreach ($all as $cat => $items) {
                        static::$byCategoryCache[(string) $cat] = $items;
                    }
                }
            }
        }

        // Lazy fetch for this category if not cached yet.
        if (! isset(static::$byCategoryCache[$category])) {
            static::$byCategoryCache[$category] = Course::query()
                ->where('category', $category)
                ->orderBy('title')
                ->get(['id', 'title', 'category', 'description', 'code', 'status']);
        }

        $siblings = static::$byCategoryCache[$category];

        if ($this->excludeCurrent) {
            $siblings = $siblings->where('id', '!=', $record->id);
        }

        $visible = $siblings->take($this->limit);
        $extraCount = max($siblings->count() - $this->limit, 0);

        // Stable per-category dot color.
        $dotClass = $this->dotPalette[abs(crc32($category)) % count($this->dotPalette)];

        // Trimmer for descriptions (avoid overly long lines).
        $trim = function (?string $text, int $max = 140): string {
            $text = (string) $text;
            $text = trim(preg_replace('/\s+/', ' ', $text));
            return mb_strlen($text) > $max ? mb_substr($text, 0, $max - 1) . '…' : $text;
        };

        // Build HTML
        ob_start();

        foreach ($visible as $item) {
            /** @var \App\Models\LCourse $item */
            $url = $this->resourceClass
                ? $this->resourceClass::getUrl($this->linkAction, ['record' => $item])
                : '#';
            ?>
            <a href="<?= e($url) ?>" wire:navigate
               class="group flex items-center gap-3 rounded-lg px-3 py-2.5 transition-all duration-150 hover:bg-gray-50 dark:hover:bg-white/5">
                <div class="flex h-1.5 w-1.5 shrink-0 rounded-full <?= e($dotClass) ?> opacity-40 transition-opacity group-hover:opacity-100"></div>

                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-2">
                        <p class="truncate text-sm font-medium text-gray-700 transition-colors group-hover:text-gray-950 dark:text-gray-300 dark:group-hover:text-white">
                            <?= e($item->title) ?>
                        </p>

                        <span class="shrink-0 rounded-md bg-gray-100 px-1.5 py-0.5 text-[10px] font-medium leading-tight text-gray-500 transition-colors group-hover:bg-gray-200/70 group-hover:text-gray-600 dark:bg-white/5 dark:text-gray-500 dark:group-hover:bg-white/10 dark:group-hover:text-gray-400">
                            <?= e($item->code) ?>
                        </span>
                    </div>

                    <?php if (filled($item->description)) : ?>
                        <p class="mt-0.5 text-xs leading-relaxed text-gray-400 dark:text-gray-500">
                            <?= e($trim($item->description)) ?>
                        </p>
                    <?php endif; ?>
                </div>

            </a>
            <?php
        }

        if ($extraCount > 0) {
            ?>
            <div class="px-3 pt-1 text-xs text-gray-500 dark:text-gray-500">
                +<?= (int) $extraCount ?> more in this category
            </div>
            <?php
        }

        return ob_get_clean();
    }
}
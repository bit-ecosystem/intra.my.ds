<?php

declare(strict_types=1);

namespace App\Filament\Staff\Pages;

use App\Models\Core\HelpPage;
use BackedEnum;
use Filament\Pages\Page;
use Illuminate\Support\Str;
use UnitEnum;
use Illuminate\Contracts\Support\Htmlable;

class Help extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-book-open';

    protected static ?int $navigationSort = 4;
    // public function getTitle(): string | Htmlable
    // { return __('Assigned Assets'); }
    // public static function getNavigationLabel(): string
    // { return __('Assigned Assets'); }
    public static function getNavigationGroup(): string | UnitEnum | null
    { return __('Knowledge'); }
    // public function getSubheading(): ?string
    // { return __('Asset/Equipment/Items issued to you or your support group.'); }
    
    protected string $view = 'filament.staff.pages.help';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    // protected static ?string $slug;
    protected static ?string $slug = 'help/{slug}';

    public ?HelpPage $record = null;

    public ?string $slugParam = null;

    // public static ?string $title = null;
    public string $html = '<h2>Not found</h2><p>No help page for this slug.</p>';

    public function mount(string $slug): void
    {
        $this->slugParam = $slug;

        $record = HelpPage::query()->where('slug', $slug)->first();

        if ($record) {
            // $this->html = Str::markdown($record->markdown ?? '', [
            //     'html_input' => 'strip',
            //     'allow_unsafe_links' => false,
            // ]);
            $this->html = $record->markdown ?? '';

            dd($this->html);
            // Separate visible H1 from <title> if you want
            $computed = $record->title ?: Str::headline($slug);
            $this->heading = $computed;                          // H1 under breadcrumb
            // $this->title   = $computed;
        } else {
            $computed = Str::headline($slug);
            $this->heading = $computed;
            // $this->title   = $computed;
        }
    }

    public static function routePattern(): string
    {
        return 'help/{slug}';
    }

    public function getHeading(): string
    {
        return $this->heading ?? Str::headline($this->slugParam ?? 'Help');
    }

    public function getTitle(): string
    {
        // Browser tab title; can differ from heading if you want
        return $this->title ?? $this->getHeading();
    }
}

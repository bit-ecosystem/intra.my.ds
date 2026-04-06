<?php

declare(strict_types=1);

namespace App\Filament\Staff\Pages;

use BackedEnum;
use Bites\Shared\Concerns\HasHelp;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use UnitEnum;

class Location extends Page implements HasActions, HasForms, HasTable
{
    use HasHelp;
    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithTable;

    protected static string|BackedEnum|null $navigationIcon = 'myicon-location';

    protected static ?int $navigationSort = 51;

    public function getTitle(): string|Htmlable
    {
        return __('Floor Plan');
    }

    public static function getNavigationLabel(): string
    {
        return __('Floor Plan');
    }

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return __('Location');
    }

    public function getSubheading(): ?string
    {
        return __('Links to floor plans and maps of the organization buildings and campuses. Ideally includes registered storage locations.');
    }

    protected string $view = 'filament.staff.pages.location';

    public ?string $scope = 'all';

    public function locationInfolist(Schema $schema): Schema
    {
        return $schema
            ->state(config('bites.emergency', []))
            ->schema([
                Section::make('1st Floor')
                    ->description('Hold Ctrl and scroll to zoom')
                    ->columnSpanFull()
                    ->schema([
                        ViewEntry::make('floor_plan')
                            ->view('filament.infolists.components.floor-plan-view')
                            ->columnSpanFull(),

                    ])

                    ->collapsed(),

                // ========= GROUND FLOOR (height-based zoom) =========
                Section::make('Ground Floor')
                    ->extraAttributes([
                        // Alpine state lives here
                        'x-data' => '{
                    zoom: 1,
                    zoomMin: 0.5,
                    zoomMax: 3,
                    zoomStep: 0.1,}',
                        'id' => 'ground-floor-container',
                    ])
                // ->headerActions([
                //     Action::make('zoomIn')
                //         ->iconButton()
                //         ->icon('heroicon-m-magnifying-glass-plus')
                //         ->extraAttributes([
                //             // Directly change height (no custom event)
                //             '@click' => 'height = Math.min(max, height + step)',
                //         ]),

                //     Action::make('zoomOut')
                //         ->iconButton()
                //         ->icon('heroicon-m-magnifying-glass-minus')
                //         ->extraAttributes([
                //             '@click' => 'height = Math.max(min, height - step)',
                //         ]),

                //     Action::make('resetZoom')
                //         ->iconButton()
                //         ->icon('myicon-refresh2')
                //         ->tooltip('Reset zoom')
                //         ->extraAttributes([
                //             '@click' => 'height = 800',
                //         ]),
                // ])
                    ->schema([
                        ImageEntry::make('floor_plan_g')
                            ->hiddenLabel()
                            ->state(asset('images/floorplan_G.png'))
                            ->extraImgAttributes([
                                'class' => 'max-w-none transition-all duration-300 select-none',
                                'x-bind:style' => '`height: ${height}px`',
                            ])
                            ->extraAttributes([
                                'style' => 'max-height: 600px; overflow: auto;',
                                'class' => 'ring-1 ring-gray-200 rounded-lg bg-gray-50 p-2',
                            ]),
                    ])
                    ->columnSpanFull()
                    ->collapsed(),
                // ========= END GROUND FLOOR =========
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                return \Bites\Core\Organization\Location::query()
                    ->when($this->scope === 'rooms', fn ($q) => $q->where('type', 'room'))
                    ->when($this->scope === 'stores', fn ($q) => $q->where('type', 'store'))
                    ->when($this->scope === 'inactive', fn ($q) => $q->whereNotNull('ends_at'));
            })
            ->paginated(['all'])
            ->columns([
                TextColumn::make('code')->label('Code'),
                TextColumn::make('full_path')->label('Location Hierarchy'),
                TextColumn::make('description')->label('Description'),
            ])
            ->recordActions([
                //
            ])
            ->headerActions([
                Action::make('all')
                    ->label('All')
                    ->icon('heroicon-o-rectangle-stack')
                    ->color(fn (): string => $this->scope === 'all' ? 'primary' : 'gray')
                    ->outlined(fn (): bool => $this->scope !== 'all')
                    ->action(function (): void {
                        $this->scope = 'all';
                        $this->resetTablePage();
                    })
                    ->badge(\Bites\Core\Organization\Location::count()),

                Action::make('rooms')
                    ->label('Rooms')
                    ->icon('heroicon-o-home-modern')
                    ->color(fn (): string => $this->scope === 'rooms' ? 'primary' : 'gray')
                    ->outlined(fn (): bool => $this->scope !== 'rooms')
                    ->action(function (): void {
                        $this->scope = 'rooms';
                        $this->resetTablePage();
                    })
                    ->badge(\Bites\Core\Organization\Location::where('type', 'room')->count()),

                Action::make('stores')
                    ->label('Stores')
                    ->icon('heroicon-o-building-storefront')
                    ->color(fn (): string => $this->scope === 'stores' ? 'primary' : 'gray')
                    ->outlined(fn (): bool => $this->scope !== 'stores')
                    ->action(function (): void {
                        $this->scope = 'stores';
                        $this->resetTablePage();
                    })
                    ->badge(\Bites\Core\Organization\Location::where('type', 'store')->count()),

                Action::make('inactive')
                    ->label('Inactive')
                    ->icon('heroicon-o-archive-box')
                    ->color(fn (): string => $this->scope === 'inactive' ? 'warning' : 'gray')
                    ->outlined(fn (): bool => $this->scope !== 'inactive')
                    ->action(function (): void {
                        $this->scope = 'inactive';
                        $this->resetTablePage();
                    })
                    ->badge(\Bites\Core\Organization\Location::whereNotNull('ends_at')->count()),
            ]);
    }
}

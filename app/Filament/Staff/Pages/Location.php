<?php

declare(strict_types=1);

namespace App\Filament\Staff\Pages;

use BackedEnum;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Infolists\Components\ImageEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use UnitEnum;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Schemas\Components\Tabs\Tab;
use App\Enums\LocationType;

class Location extends Page implements HasActions, HasForms, HasTable
{
    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithTable;

    protected static ?string $title = 'Floor Plan';

    protected static string|UnitEnum|null $navigationGroup = 'Location';

    protected static string|BackedEnum|null $navigationIcon = 'myicon-location';

    protected static ?int $navigationSort = 51;

    protected string $view = 'filament.staff.pages.location';

    public ?string $scope = 'all';

    public function getSubheading(): ?string
    {
        return __('Links to floor plans and maps of the organization buildings and campuses. Ideally includes registered storage locations.');
    }

    public function locationInfolist(Schema $schema): Schema
    {
        return $schema
            ->state(config('bites.emergency', []))
            ->schema([
                Section::make('1st Floor')
                ->extraAttributes([
                        // Alpine state lives here
                        'x-data' => '{ height: 800, min: 200, max: 3000, step: 200 }',
                        'id' => 'ground-floor-container',
                    ])
                    ->headerActions([
                        Action::make('zoomIn')
                            ->iconButton()
                            ->icon('heroicon-m-magnifying-glass-plus')
                            ->extraAttributes([
                                // Directly change height (no custom event)
                                '@click' => 'height = Math.min(max, height + step)',
                            ]),

                        Action::make('zoomOut')
                            ->iconButton()
                            ->icon('heroicon-m-magnifying-glass-minus')
                            ->extraAttributes([
                                '@click' => 'height = Math.max(min, height - step)',
                            ]),

                        Action::make('resetZoom')
                            ->iconButton()
                            ->icon('myicon-refresh2')
                            ->tooltip('Reset zoom')
                            ->extraAttributes([
                                '@click' => 'height = 800',
                            ]),
                    ])
                    ->schema([
                        ImageEntry::make('floor_plan')
                            ->hiddenLabel()
                            ->state(asset('images/floorplan_1.png'))
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

                // ========= GROUND FLOOR (height-based zoom) =========
                Section::make('Ground Floor')
                    ->extraAttributes([
                        // Alpine state lives here
                        'x-data' => '{ height: 800, min: 200, max: 3000, step: 200 }',
                        'id' => 'ground-floor-container',
                    ])
                    ->headerActions([
                        Action::make('zoomIn')
                            ->iconButton()
                            ->icon('heroicon-m-magnifying-glass-plus')
                            ->extraAttributes([
                                // Directly change height (no custom event)
                                '@click' => 'height = Math.min(max, height + step)',
                            ]),

                        Action::make('zoomOut')
                            ->iconButton()
                            ->icon('heroicon-m-magnifying-glass-minus')
                            ->extraAttributes([
                                '@click' => 'height = Math.max(min, height - step)',
                            ]),

                        Action::make('resetZoom')
                            ->iconButton()
                            ->icon('myicon-refresh2')
                            ->tooltip('Reset zoom')
                            ->extraAttributes([
                                '@click' => 'height = 800',
                            ]),
                    ])
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
                    ->collapsed()
                // ========= END GROUND FLOOR =========
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                return \App\Models\Core\Location::query()
                    ->when($this->scope === 'rooms', fn($q) => $q->where('type', 'room'))
                    ->when($this->scope === 'stores', fn($q) => $q->where('type', 'store'))
                    ->when($this->scope === 'inactive', fn($q) => $q->whereNotNull('ends_at'));
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
                    ->color(fn() => $this->scope === 'all' ? 'primary' : 'gray')
                    ->outlined(fn() => $this->scope !== 'all')
                    ->action(function () {
                        $this->scope = 'all';
                        $this->resetTablePage();
                    })
                    ->badge(\App\Models\Core\Location::count()),

                Action::make('rooms')
                    ->label('Rooms')
                    ->icon('heroicon-o-home-modern')
                    ->color(fn() => $this->scope === 'rooms' ? 'primary' : 'gray')
                    ->outlined(fn() => $this->scope !== 'rooms')
                    ->action(function () {
                        $this->scope = 'rooms';
                        $this->resetTablePage();
                    })
                    ->badge(\App\Models\Core\Location::where('type', 'room')->count()),

                Action::make('stores')
                    ->label('Stores')
                    ->icon('heroicon-o-building-storefront')
                    ->color(fn() => $this->scope === 'stores' ? 'primary' : 'gray')
                    ->outlined(fn() => $this->scope !== 'stores')
                    ->action(function () {
                        $this->scope = 'stores';
                        $this->resetTablePage();
                    })
                    ->badge(\App\Models\Core\Location::where('type', 'store')->count()),

                Action::make('inactive')
                    ->label('Inactive')
                    ->icon('heroicon-o-archive-box')
                    ->color(fn() => $this->scope === 'inactive' ? 'warning' : 'gray')
                    ->outlined(fn() => $this->scope !== 'inactive')
                    ->action(function () {
                        $this->scope = 'inactive';
                        $this->resetTablePage();
                    })
                    ->badge(\App\Models\Core\Location::whereNotNull('ends_at')->count()),
            ]);
    }
}

<?php

declare(strict_types=1);

namespace App\Filament\Staff\Pages;

use BackedEnum;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Actions\Action;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use UnitEnum;

class Location extends Page
{
    protected static ?string $title = 'Floor Plan';

    protected static string|UnitEnum|null $navigationGroup = 'Location';

    protected static string|BackedEnum|null $navigationIcon = 'myicon-location';

    protected static ?int $navigationSort = 51;

    protected string $view = 'filament.staff.pages.location';

    public function getSubheading(): ?string
    {
        return __('Links to floor plans and maps of the organization buildings and campuses. Ideally includes registered storage locations.');
    }
    public function locationInfolist(Schema $schema): Schema
    {
        return $schema
            // Point state to config('bites.emergency')
            ->state(config('bites.emergency', []))
            ->schema([
                Section::make('1st Floor')
                    ->schema([
                        ImageEntry::make('floor_plan')
                            ->hiddenLabel()
                            ->state(asset('images/floorplan_1.png'))
                            // Remove Filament's default width/height constraints 
                            // so the image retains its original size for scrolling
                            ->imageHeight(1000)
                            ->extraImgAttributes([
                                'class' => 'max-w-none', // Prevents image from shrinking to fit
                            ])->extraAttributes([
                                'style' => 'max-height: 500px; overflow: auto;',
                                'class' => 'ring-1 ring-gray-200 rounded-lg'
                            ]),
                    ])->columnSpanFull()->collapsed(),

                Section::make('Ground Floor')
                    ->headerActions([
                        Action::make('zoomIn')
                            ->iconButton()
                            ->icon('heroicon-m-magnifying-glass-plus')
                            ->extraAttributes(['x-on:click' => 'height += 200']), // Increment height
                        Action::make('zoomOut')
                            ->iconButton()
                            ->icon('heroicon-m-magnifying-glass-minus')
                            ->extraAttributes(['x-on:click' => 'height = Math.max(200, height - 200)']), // Decrement height
                    ])
                    ->schema([
                        ImageEntry::make('floor_plan')
                            ->hiddenLabel()
                            ->state(asset('images/floorplan_G.png'))
                            ->extraImgAttributes([
                                'class' => 'max-w-none transition-all duration-300',
                                ':style' => '`height: ${height}px`', // Alpine.js dynamic height
                            ])
                            ->extraAttributes([
                                'style' => 'max-height: 600px; overflow: auto;',
                                'class' => 'ring-1 ring-gray-200 rounded-lg bg-gray-50'
                            ]),
                    ])
                    ->columnSpanFull()
                    ->collapsed()
                    // Initialize the Alpine state on the section container
                    ->extraAttributes(['x-data' => '{ height: 1000 }'])

            ]);
    }
}

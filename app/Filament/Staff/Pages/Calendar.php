<?php

declare(strict_types=1);

namespace App\Filament\Staff\Pages;

use App\Enums\EventType;
use App\Models\Event;
use BackedEnum;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
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
use Illuminate\Contracts\View\View;
use UnitEnum;

class Calendar extends Page implements HasActions, HasForms, HasTable
{
    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithTable;

    protected string $view = 'filament.staff.pages.calendar';

    protected static string|UnitEnum|null $navigationGroup = 'To Do';

    protected static string|BackedEnum|null $navigationIcon = 'myicon-s-calendar';

    protected static ?int $navigationSort = 12;

    public $events;

    public function getSubheading(): ?string
    {
        return __('Calendar view of workdays and events in ATM.');
    }

    protected function getHeaderWidgets(): array
    {
        return [
            // \App\Filament\Staff\Widgets\CalendarWidget::class,
            // \App\Filament\Staff\Widgets\TestCal::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            // \App\Filament\Core\Resources\HelpPages\Actions\OpenHelpAction::make(static::class),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                \App\Models\Event::query()
            )
            ->paginated(['all'])
            ->columns([
                TextColumn::make('title')->label('Title'),
                TextColumn::make('description')->label('Description'),
                ColorColumn::make('color')->label('Event Color')->sortable(),
                TextColumn::make('starts_at')->date('D M j, Y')->label('Date')->sortable(),
                // TextColumn::make('ends_at')->date()->label('End Date'),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->multiple()
                    ->options(
                        collect(\App\Enums\EventType::cases())->mapWithKeys(function ($case): array {
                            return [$case->value => $case->getLabel()];
                        })->toArray()
                    ),
            ])
            ->recordActions([

                EditAction::make()
                    ->schema([
                        Forms\Components\Select::make('type')->label('Event Type')
                            ->options(
                                collect(\App\Enums\EventType::cases())->mapWithKeys(function ($case): array {
                                    return [$case->value => $case->getLabel()];
                                })->toArray()
                            )
                            ->required()
                            ->live() // make it reactive
                            ->afterStateUpdated(function ($state, Set $set): void {
                                if (blank($state)) {
                                    $set('color', null);

                                    return;
                                }

                                // Map the selected string value back to enum and set color
                                $set('color', EventType::from($state)->getColor()[300]);
                            }),
                        Forms\Components\ColorPicker::make('color')
                            ->placeholder(null)
                            ->label('Event Color')
                            ->disabled()     // read-only in UI
                            ->dehydrated(),  // still gets saved to the database
                    ]),
            ])
            ->toolbarActions([
                CreateAction::make()
                    ->model(Event::class)
                    ->createAnother(false)
                    ->schema([
                        Forms\Components\TextInput::make('title')->label('Title'),
                        Flex::make([
                            Forms\Components\DateTimePicker::make('starts_at')->label('Start Date'),
                            Forms\Components\DateTimePicker::make('ends_at')->label('End Date'),
                            Forms\Components\Select::make('type')->label('Event Type')
                                ->options(
                                    collect(\App\Enums\EventType::cases())->mapWithKeys(function ($case): array {
                                        return [$case->value => $case->getLabel()];
                                    })->toArray()
                                )
                                ->required()
                                ->live() // make it reactive
                                ->afterStateUpdated(function ($state, Set $set): void {
                                    if (blank($state)) {
                                        $set('color', null);

                                        return;
                                    }

                                    // Map the selected string value back to enum and set color
                                    $set('color', EventType::from($state)->getColor()[300]);
                                }),
                            Forms\Components\ColorPicker::make('color')
                                ->placeholder(null)
                                ->label('Event Color')
                                ->disabled()     // read-only in UI
                                ->dehydrated(),  // still gets saved to the database
                        ]),
                    ]),
            ]);
    }

    public function render(): View
    {
        // Returns a LengthAwarePaginator of the *current page* after filters/search/sort
        $paginator = $this->getTableRecords();

        $events = collect($paginator->items())->map(function (Event $event) {
            return [
                'title' => $event->title,
                'start' => optional($event->starts_at)->toIso8601String(),
                'end' => optional($event->ends_at)->toIso8601String(),
                // 'textColor'  => '#000000',
                'color' => $event->color,
                // 'backgroundColor'  => '#ffffff',
                // 'borderColor'  => $event->color,
                'allDay' => $event->is_all_day,
            ];
        })->values();

        $this->events = $events->toJson();

        return parent::render();
    }
}

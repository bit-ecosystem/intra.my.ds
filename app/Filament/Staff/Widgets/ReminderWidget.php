<?php

namespace App\Filament\Staff\Widgets;

use App\Models\PersonAttribute;
use App\Models\User;
use Carbon\Carbon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Support\Facades\Auth;

class ReminderWidget extends TableWidget
{
    protected static ?string $heading = 'Personal Reminders';
    // protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->records(function (): array {
                $pa = PersonAttribute::where('key', 'reminders')
                    ->where('attributable_type', User::class)
                    ->where('attributable_id', Auth::id())
                    ->first();

                if (! $pa) {
                    return [];
                }

                $raw = $pa->value;

                // Decode safely
                if (is_array($raw)) {
                    $items = $raw;
                } elseif (is_string($raw)) {
                    $decoded = json_decode($raw, true);
                    $items = is_array($decoded) ? $decoded : [];
                } else {
                    $items = [];
                }

                // --------- ✅ FILTERING LOGIC ---------
                $today = now();
                $limit = now()->addDays(14);

                $filtered = collect($items)->filter(function ($item) use ($today, $limit) {
                    if (! isset($item['expiry_date'])) {
                        return false;
                    }

                    $date = Carbon::parse($item['expiry_date']);

                    return
                        $date->isPast() ||                // ✅ Already expired
                        ($date->between($today, $limit)); // ✅ Within 2 weeks
                });
                // --------------------------------------

                // Build rows
                return $filtered
                    ->values()
                    ->map(fn ($item, $index) => [
                        'id' => $index,
                        'name' => $item['name'] ?? '(No name)',
                        'expiry_date' => $item['expiry_date'] ?? null,
                    ])
                    ->all();
            })
            ->columns([
                TextColumn::make('name')
                    ->label('Item'),
                TextColumn::make('expiry_date')
                    ->label('Expires')
                    ->since()
                    ->dateTooltip()
                    // ->formatStateUsing(fn($state) => \Carbon\Carbon::parse($state)->toFormattedDateString())
                    ->color(fn ($state) => Carbon::parse($state)->isPast() ? 'danger' : 'info'),

            ])
            ->recordUrl(fn ($record) => route('filament.staff.pages.biodata', [
                'step' => 'reminders',
            ]))
            ->filters([
                //
            ]);
    }
}

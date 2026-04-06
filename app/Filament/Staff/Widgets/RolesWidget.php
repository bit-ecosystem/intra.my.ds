<?php

declare(strict_types=1);

namespace App\Filament\Staff\Widgets;

use Bites\Core\Organization\OrgUnit;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class RolesWidget extends TableWidget
{
    protected int|string|array $columnSpan = 3;

    protected static ?string $heading = 'My Roles';

    public function table(Table $table): Table
    {
        $roleids = array_values(Auth::user()->roles->pluck('id')->toArray()) ?? [];

        return $table
            ->query(
                Role::withoutGlobalScopes()
                    ->whereIn('id', $roleids)
            )
            ->paginated(false)
            ->columns([
                Split::make([
                    Stack::make([
                        TextColumn::make('name')
                            // ->badge('primary')
                            ->searchable(),
                        // TextColumn::make('guard_name')
                        //     ->searchable(),
                        TextColumn::make('description')
                            ->searchable(),
                    ]),
                    TextColumn::make('team_id')
                        ->label('OU code')
                        ->formatStateUsing(function (?string $state) {
                            if (! $state) {
                                return '—';
                            }

                            static $cache = [];
                            if (! array_key_exists($state, $cache)) {
                                $cache[$state] = OrgUnit::whereKey($state)->value('code') ?? 'OU #'.$state;
                            }

                            return $cache[$state];
                        }),
                ]),
                // TextColumn::make('created_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
                // TextColumn::make('updated_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}

<?php

declare(strict_types=1);

namespace App\Filament\Staff\Widgets;

use App\Models\Workflow\Node;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class RequestWidget extends TableWidget
{
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table

            ->query(function (Builder $builder) {
                $user = Auth::user();

                // Super-admin bypass (optional)
                if ($user->hasRole('su')) {
                    return Node::query()->where('is_initial', true);
                }

                $roleIds = $user->roles->pluck('id'); // Spatie roles relation

                return Node::query()
                    ->where('is_initial', true)
                    ->whereIn('assignee_role_id', $roleIds);
            })
            ->columns([
                TextColumn::make('workflow.name')
                    ->searchable(),
                TextColumn::make('name')
                    ->searchable(),
                IconColumn::make('is_initial')
                    ->boolean(),
                IconColumn::make('is_final')
                    ->boolean(),
                TextColumn::make('assigneeRole.name')
                    ->searchable(),
                TextColumn::make('sort')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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

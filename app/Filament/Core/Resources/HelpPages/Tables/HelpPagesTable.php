<?php

declare(strict_types=1);

namespace App\Filament\Core\Resources\HelpPages\Tables;

use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Tables\Columns;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class HelpPagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Columns\TextColumn::make('panel_id')
                    ->label('Panel')
                    ->badge()
                    ->colors(['primary'])
                    ->sortable(),

                Columns\TextColumn::make('resource_class')
                    ->label('Resource')
                    ->limit(40)
                    ->tooltip(fn ($record) => $record->resource_class)
                    ->searchable(),

                Columns\TextColumn::make('page_class')
                    ->label('Page')
                    ->limit(40)
                    ->tooltip(fn ($record) => $record->page_class)
                    ->searchable(),

                Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->copyable()
                    ->copyMessage('Slug copied!')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn ($state): string => '/knowledge/'.$state),

                Columns\TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),

                Columns\TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Tables\Filters\SelectFilter::make('panel_id')
                //     ->label('Panel')
                //     ->options(collect(Filament::getPanels())
                //         ->mapWithKeys(fn($panel) => [$panel->getId() => Str::headline($panel->getId())])
                //         ->all()),
            ])
            ->recordActions([
                // Tables\Actions\EditAction::make(),

                // Preview rendered markdown in a modal
                Actions\Action::make('preview')
                    ->label('Preview')
                    ->icon('heroicon-o-eye')
                    ->modalHeading(fn ($record): string => 'Preview: '.($record->title ?? $record->slug))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close')
                    ->schema(function ($record): array {
                        // Render markdown safely
                        $html = Str::markdown($record->markdown ?? '', [
                            'html_input' => 'strip',
                            'allow_unsafe_links' => false,
                        ]);
                        dd($html);

                        return [
                            \Filament\Infolists\Components\TextEntry::make('content')
                                ->label('')
                                ->html($html),
                        ];
                    }),

                // Open the knowledge page route
                Actions\Action::make('open')
                    ->label('Open Help')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(function ($record): void {
                        $panelId = Filament::getCurrentPanel()?->getId() ?? 'admin';

                        // return route('filament.staff.pages.knowledge', ['slug' => $record->slug]);
                        // return route("filament.{$panelId}.pages.knowledge", ['slug' => $record->slug]);
                    })
                    ->openUrlInNewTab(),
            ]);
    }
}

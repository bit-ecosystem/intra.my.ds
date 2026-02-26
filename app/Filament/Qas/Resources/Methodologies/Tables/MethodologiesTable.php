<?php

declare(strict_types=1);

namespace App\Filament\Qas\Resources\Methodologies\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MethodologiesTable
{
    // public static function configure(Table $table): Table
    // {
    //     return $table
    //         ->columns([
    //             TextColumn::make('methodology')
    //                 ->searchable(),
    //             TextColumn::make('purpose')
    //                 ->searchable(),
    //             IconColumn::make('needs_form')
    //                 ->boolean(),
    //             IconColumn::make('needs_report')
    //                 ->boolean(),
    //             TextColumn::make('typical_record_type')
    //                 ->searchable(),
    //             TextColumn::make('form_schema')
    //                 ->searchable(),
    //             TextColumn::make('example_template_name')
    //                 ->searchable(),
    //             TextColumn::make('external_url')
    //                 ->searchable(),
    //             TextColumn::make('created_at')
    //                 ->dateTime()
    //                 ->sortable()
    //                 ->toggleable(isToggledHiddenByDefault: true),
    //             TextColumn::make('updated_at')
    //                 ->dateTime()
    //                 ->sortable()
    //                 ->toggleable(isToggledHiddenByDefault: true),
    //         ])
    //         ->filters([
    //             //
    //         ])
    //         ->recordActions([
    //             ViewAction::make(),
    //             EditAction::make(),
    //         ])
    //         ->toolbarActions([
    //             BulkActionGroup::make([
    //                 DeleteBulkAction::make(),
    //             ]),
    //         ]);
    // }

    public static function configure(Table $table): Table
    {
        return $table
            // ->modifyQueryUsing(\App\Filament\Core\Resources\Roles\Schemas\RoleCanView::tableVisibilityModifier(['su' => '153582']))
            ->columns([
                Columns\Layout\Split::make([
                    Columns\ImageColumn::make('icon')
                        ->label('')
                        ->circular()
                        ->grow(false)
                        ->defaultImageUrl('https://raw.githubusercontent.com/bit-ecosystem/bites/refs/heads/main/menu/business-idea.svg'), // to chanage to Str::kebab($record->title)
                    Columns\Layout\Stack::make([
                        Columns\TextColumn::make('title')
                            ->label('Title')
                            // ->searchable()
                            ->color('primary'),
                        Columns\TextColumn::make('description')
                            ->size(\Filament\Support\Enums\TextSize::ExtraSmall)
                            ->wrap(),
                    ]),
                ]),
            ])
            ->paginated(false)
            ->contentGrid([
                'md' => 2,
                'xl' => 4,
            ])
            // ->recordUrl(
            //     fn (Model $model): string => $model->internal_link && Route::has($model->internal_link)
            //         ? route($model->internal_link)
            //         : ($model->attachableLink()->latest()->value('url') ?? '#')
            // )
            ->filters([])
            ->toolbarActions([]);
    }
}
<?php

declare(strict_types=1);

namespace App\Filament\Staff\Resources\Menus\Tables;

use Filament\Support\Enums\TextSize;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;

class MenusTable
{
    public static function configure(Table $table): Table
    {
        return $table
            // ->modifyQueryUsing(\App\Filament\Core\Resources\Roles\Schemas\RoleCanView::tableVisibilityModifier(['su' => '153582']))
            ->columns([
                Split::make([
                    ImageColumn::make('icon')
                        ->label('')
                        ->circular()
                        ->grow(false)
                        ->defaultImageUrl('https://raw.githubusercontent.com/bit-ecosystem/bites/refs/heads/main/menu/business-idea.svg'), // to chanage to Str::kebab($record->title)
                    Stack::make([
                        TextColumn::make('title')
                            ->label('Title')
                            // ->searchable()
                            ->color('primary'),
                        TextColumn::make('description')
                            ->size(TextSize::ExtraSmall)
                            ->wrap(),
                    ]),
                ]),
            ])
            ->paginated(false)
            ->contentGrid([
                'md' => 2,
                'xl' => 4,
            ])
            ->recordUrl(
                fn (Model $model): string => $model->internal_link && Route::has($model->internal_link)
                    ? route($model->internal_link)
                    : ($model->attachableLink()->latest()->value('url') ?? '#')
            )
            ->filters([])
            ->toolbarActions([]);
    }
}

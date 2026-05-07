<?php

declare(strict_types=1);

namespace App\Filament\Lms\Resources\Courses\RelationManagers;

use App\Filament\Lms\Resources\Modules\ModuleResource;
use Filament\Actions;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class ModulesRelationManager extends RelationManager
{
    protected static string $relationship = 'modules'; // Course::modules()

    protected static ?string $relatedResource = ModuleResource::class;

    public function table(Table $table): Table
    {

        return $table
            ->modifyQueryUsing(function ($query) {
                return $this->ownerRecord
                    ->modules()
                    ->getQuery(); // 🔥 FORCE correct query
            })
            ->headerActions([
                CreateAction::make(),
            ])

            ->recordActions([

                // Actions\ViewAction::make(),

                Actions\Action::make('openLms')
                    ->label('View')
                    ->icon('heroicon-m-arrow-top-right-on-square') // nice "external" icon
                    ->color('primary')
                    ->url(fn ($record): string => 'https://intra.my.ds.amkor.com/lms/modules/'.$record->getKey()),

            ]);
    }
}

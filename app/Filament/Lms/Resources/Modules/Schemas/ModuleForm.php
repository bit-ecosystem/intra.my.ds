<?php

declare(strict_types=1);

// declare(strict_types=1);

namespace App\Filament\Lms\Resources\Modules\Schemas;

use App\Filament\Core\Resources\Roles\Schemas\RoleCanView;
use Filament\Forms\Components;
use Filament\Schemas\Schema;

class ModuleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Components\TextInput::make('slug')
                    ->required(),
                Components\TextInput::make('title')
                    ->required(),
                Components\Textarea::make('description')
                    ->columnSpanFull(),
                Components\TextInput::make('order_index')
                    ->required()
                    ->numeric()
                    ->default(0),
                Components\TextInput::make('estimated_duration_minutes')
                    ->numeric(),
                Components\TextInput::make('validity_months')
                    ->numeric(),
                Components\Textarea::make('certificate_template')
                    ->columnSpanFull(),
                ...RoleCanView::formComponents(
                    relationship: 'attachableRoles', // your morphToMany on the model
                    showSelect: false,               // keep the Select hidden (state updated by the Action)
                    actionName: 'choose_roles', // rename if you include twice in same form

                    //                 recordId: fn (array $record): string {
                    //     return $record['id'] ?? '';
                    // },
                ),
            ]);
    }
}

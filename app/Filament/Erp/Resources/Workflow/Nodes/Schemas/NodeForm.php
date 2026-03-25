<?php

declare(strict_types=1);

namespace App\Filament\Erp\Resources\Workflow\Nodes\Schemas;

use App\Filament\Core\Resources\Roles\Schemas\RoleCanView;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class NodeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('workflow_id')
                    ->relationship('workflow', 'name')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                Toggle::make('is_initial')
                    ->required(),
                Toggle::make('is_final')
                    ->required(),
                TextInput::make('sort')
                    ->required()
                    ->numeric()
                    ->default(0),

                ...RoleCanView::formComponents(
                    relationship: 'attachableRoles', // your morphToMany on the model
                    showSelect: false,               // keep the Select hidden (state updated by the Action)
                    actionName: 'choose_roles',      // rename if you include twice in same form
                    superUserRole: 'ou_member',
                ),
            ]);
    }
}

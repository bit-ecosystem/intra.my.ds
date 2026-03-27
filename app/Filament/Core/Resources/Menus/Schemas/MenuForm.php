<?php

declare(strict_types=1);

namespace App\Filament\Core\Resources\Menus\Schemas;

use App\Enums\MenuCategory;
use Bites\Shared\Concerns\HasAttachableExtLink;
use Filament\Forms\Components;
use Filament\Schemas\Schema;

class MenuForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Components\Select::make('category')->required()
                ->options(
                    collect(MenuCategory::cases())->mapWithKeys(function ($case): array {
                        return [$case->value => $case->getLabel()];
                    })->toArray()
                ),
            Components\FileUpload::make('icon')->image()->imageEditor(),
            // Components\TextInput::make('icon'),
            Components\TextInput::make('title')->columnSpanFull()->required(),
            Components\Textarea::make('description')->columnSpanFull(),
            HasAttachableExtLink::FormComponent(),
            Components\Select::make('attachableRoles')
                ->relationship(name: 'attachableRoles', titleAttribute: 'name'),
            Components\TextInput::make('internal_link'),
        ]);
    }
}

<?php

declare(strict_types=1);

namespace App\Filament\Hrm\Resources\OrgUnits\Schemas;

use Bites\Organization\Structure\JobPosition;
use Filament\Forms;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class OrgUnitForm
{
    public static function configure(Schema $schema): Schema
    {

        return $schema
            ->components([
                Forms\Components\TextInput::make('name')
                    ->label('Org Unit Name')
                    ->required(),

                Forms\Components\Textarea::make('description')
                    ->label('Description')
                    ->rows(3),

                Forms\Components\Select::make('company_id')
                    ->label('Company')
                    ->relationship('company', 'name'),

                Forms\Components\Select::make('parent_id')
                    ->label('Parent Org Unit')
                    ->relationship('parent', 'name'),
                Forms\Components\Hidden::make('id')->dehydrated(false),

                Forms\Components\Select::make('owner_id')
                    ->label('Owner Job Position')
                    ->helperText('Assign a Job Position as the owner of this Org Unit')
                    ->relationship(
                        name: 'owner',
                        titleAttribute: 'title',
                        modifyQueryUsing: function (Builder $builder, Get $get): void {
                            $builder->where('org_unit_id', $get('id'));
                        }
                    )
                    ->hiddenOn('create')
                    ->options(function (Get $get) {
                        $orgUnitId = $get('id');
                        if (! $orgUnitId) {
                            return [];
                        }

                        return JobPosition::with('staff')
                            ->where('org_unit_id', $orgUnitId)
                            ->get()
                            ->sortBy(function ($jp): string {
                                $name = $jp->staff?->name;

                                return sprintf('%s — %s', $jp->title, $name);
                            })
                            ->mapWithKeys(function ($jp): array {
                                $name = $jp->staff?->name;

                                return [$jp->id => $name ? sprintf('%s — %s', $jp->title, $name) : $jp->title];
                            })
                            ->toArray();
                    }),

            ]);
    }
}

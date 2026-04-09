<?php

declare(strict_types=1);

namespace Bites\Organization\Identity\Resources\Users\Pages;

use Bites\Organization\Identity\Resources\Users\UserResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        // Fetch distinct provider values as an array
        $providers = DB::table('socialite_users')
            ->select('provider')
            ->distinct()
            ->pluck('provider')
            ->toArray();

        // Build tabs dynamically
        $tabs = [
            'all' => Tab::make('All'), // Default tab
            'default' => Tab::make(config('app.name'))
                ->modifyQueryUsing(
                    fn (Builder $builder) => $builder->whereNotIn('id', function ($sub): void {
                        $sub->select('user_id')->from('socialite_users');
                    })
                ),
        ];

        foreach ($providers as $provider) {

            $tabs[$provider] = Tab::make(ucfirst($provider))
                ->modifyQueryUsing(
                    fn (Builder $builder) => $builder->whereIn('id', function ($sub) use ($provider): void {
                        $sub->select('user_id')
                            ->from('socialite_users')
                            ->where('provider', $provider);
                    })
                );
        }

        return $tabs;
    }
}

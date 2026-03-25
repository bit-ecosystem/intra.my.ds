<?php

declare(strict_types=1);

namespace App\Filament\Core\Resources\Menus\Pages;

use App\Filament\Core\Resources\Menus\MenuResource;
use App\Models\Menu;
use Filament\Resources\Pages\CreateRecord;

class CreateMenu extends CreateRecord
{
    protected static string $resource = MenuResource::class;

    protected function afterCreate(): void
    {
        $this->attachOuRoles();
    }

    protected function attachOuRoles(): void
    {
        /** @var Menu $menu */
        $menu = $this->record;

        $state = $this->form->getState();
        $ouRoleIds = collect($state['ou_role_ids'] ?? [])->filter()->values()->all();
        $teamIds = collect($state['team_ids'] ?? [])->filter()->values()->all();

        if (empty($ouRoleIds) || empty($teamIds)) {
            return;
        }

        foreach ($teamIds as $teamId) {
            foreach ($ouRoleIds as $ouRoleId) {
                // syncWithoutDetaching expects: [roleId => ['team_id' => ...]]
                $menu->attachableRoles()->syncWithoutDetaching([$ouRoleId => ['team_id' => $teamId]]);
            }
        }
    }
}

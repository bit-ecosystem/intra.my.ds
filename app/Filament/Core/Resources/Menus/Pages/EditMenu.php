<?php

declare(strict_types=1);

namespace App\Filament\Core\Resources\Menus\Pages;

use App\Filament\Core\Resources\Menus\MenuResource;
use App\Models\Menu;
use Filament\Resources\Pages\EditRecord;

class EditMenu extends EditRecord
{
    protected static string $resource = MenuResource::class;

    protected function afterSave(): void
    {
        $this->syncOuRolesForTeams();
    }

    /**
     * If you prefer to REPLACE OU role attachments for the selected teams,
     * use syncRolesByIdsForTeam(), which first detaches existing pivots for that team_id.
     */
    protected function syncOuRolesForTeams(): void
    {
        /** @var Menu $menu */
        $menu = $this->record;

        $state = $this->form->getState();
        $ouRoleIds = collect($state['ou_role_ids'] ?? [])->filter()->values()->all();
        $teamIds = collect($state['team_ids'] ?? [])->filter()->values()->all();

        if (empty($teamIds)) {
            return;
        }

        foreach ($teamIds as $teamId) {
            if (empty($ouRoleIds)) {
                // Only detach OU roles for that team if none selected
                $menu->attachableRoles()->wherePivot('team_id', $teamId)->detach();

                continue;
            }

            // Replace attachments for this team
            $menu->syncRolesByIdsForTeam($ouRoleIds, $teamId);
        }
    }
}

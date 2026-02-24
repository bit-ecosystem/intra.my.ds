<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Core\OrgRole;
use App\Services\RoleSyncService;

class OrgRoleObserver
{
    public function saved(OrgRole $orgRole): void
    {
        $this->resyncOccupants($orgRole);
    }

    public function deleted(OrgRole $orgRole): void
    {
        $this->resyncOccupants($orgRole);
    }

    protected function resyncOccupants(OrgRole $orgRole): void
    {
        $position = $orgRole->jobPosition;
        if (! $position) {
            return;
        }

        $roleSyncService = app(RoleSyncService::class);
        foreach ($position->staff as $staff) {
            if ($staff->user) {
                $roleSyncService->syncFromStaff($staff->user, $staff);
            }
        }
    }
}

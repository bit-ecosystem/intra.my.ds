<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Hrm\Staff;
use App\Models\User;
use App\Services\RoleSyncService;

class StaffObserver
{
    public function created(Staff $staff): void
    {
        // app(RoleSyncService::class)->attachRoles($staff);
        if ($staff->user) {
            app(RoleSyncService::class)->syncFromStaff($staff->user, $staff);
        }
    }

    public function updated(Staff $staff): void
    {
        // app(RoleSyncService::class)->attachRoles($staff);
        $roleSyncService = app(RoleSyncService::class);
        
        if ($staff->isDirty('user_id')) {
            $oldUserId = $staff->getOriginal('user_id');
            if ($oldUserId && ($oldUser = User::find($oldUserId))) {
                $roleSyncService->syncFromStaff($oldUser, null);            // deassociated
            }

            if ($staff->user) {
                $roleSyncService->syncFromStaff($staff->user, $staff);      // new association
            }

            return;
        }

        // If employment_type / org_unit_id / job_position_id / is_ou_manager changed:
        if ($staff->user) {
            $roleSyncService->syncFromStaff($staff->user, $staff);
        }
    }

    public function deleted(Staff $staff): void
    {
        $user = $staff->user ?: User::find($staff->getOriginal('user_id'));
        if ($user) {
            app(RoleSyncService::class)->syncFromStaff($user, null);
        }
    }
}

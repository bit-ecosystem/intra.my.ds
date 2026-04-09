<?php

declare(strict_types=1);

namespace Bites\Organization\Authorization;

use Spatie\Permission\Models\Role;

class RoleMapperObserver
{
    /**
     * Handle the RoleMapper "created" event.
     */
    public function created(RoleMapper $roleMapper): void
    {
        Role::updateOrCreate(
            ['name' => $roleMapper->role_name, 'team_id' => $roleMapper->org_unit_id],
            [
                'description' => $roleMapper->label,
            ]
        );
    }

    /**
     * Handle the RoleMapper "updated" event.
     */
    public function updated(RoleMapper $roleMapper): void
    {
        $this->created($roleMapper);
    }

    /**
     * Handle the RoleMapper "deleted" event.
     */
    public function deleted(RoleMapper $roleMapper): void
    {
        //
    }

    /**
     * Handle the RoleMapper "restored" event.
     */
    public function restored(RoleMapper $roleMapper): void
    {
        //
    }

    /**
     * Handle the RoleMapper "force deleted" event.
     */
    public function forceDeleted(RoleMapper $roleMapper): void
    {
        //
    }
}

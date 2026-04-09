<?php

declare(strict_types=1);

namespace Bites\Attachables\Models\Concerns;

use Bites\Attachables\Models\RoleGroup;
use Bites\Organization\Structure\OrgUnit;

trait HasRoleGroup
{
    public function roleGroups()
    {
        return $this->belongsToMany(RoleGroup::class, 'role_group_users');
    }

    public function roleGroupIds()
    {
        return $this->roleGroups()->pluck('role_groups.id');
    }

    public function accessibleOrgUnitIds(): array
    {

        $ids = [$this->org_unit_id];

        $children = OrgUnit::where('parent_id', $this->org_unit_id)
            ->pluck('id')
            ->toArray();

        return array_merge($ids, $children);

    }
}

<?php

declare(strict_types=1);

namespace Bites\Attachables\Models\Concerns;

use App\Models\User;
use Bites\Attachables\Models\ModelAccessControl;
use Illuminate\Database\Eloquent\Builder;

trait HasAccessControl
{
    public function accessControls()
    {
        return $this->morphMany(ModelAccessControl::class, 'accessible');
    }

    public function grantAccess(
        ?int $orgUnitId,
        ?int $roleGroupId,
        string $roleType
    ) {
        return $this->accessControls()->create([
            'org_unit_id' => $orgUnitId,
            'role_group_id' => $roleGroupId,
            'role_type' => $roleType,
        ]);
    }

    public function scopeAccessibleTo(
        Builder $query,
        User $user,
        string $roleType = 'view'
    ) {

        return $query->whereHas('accessControls', function ($q) use ($user, $roleType): void {

            $q->where('role_type', $roleType)
                ->where(function ($q) use ($user): void {

                    $q->whereIn(
                        'org_unit_id',
                        $user->accessibleOrgUnitIds()
                    )
                        ->orWhereNull('org_unit_id');

                })
                ->whereIn(
                    'role_group_id',
                    $user->roleGroupIds()
                );

        });

    }
}

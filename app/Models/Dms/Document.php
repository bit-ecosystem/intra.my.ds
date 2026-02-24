<?php

declare(strict_types=1);

namespace App\Models\Dms;

use App\Models\Core\OrgUnit;
use App\Models\User;
use App\Observers\DocumentObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ObservedBy([DocumentObserver::class])]
class Document extends Model
{
    use HasFactory;

    protected $table = 'd_documents';

    protected $fillable = [
        'org_unit_id',
        'title',
        'content',
        'classification_level',
        'uploaded_by',
        'settings',
    ];

    public function orgUnit(): BelongsTo
    {
        return $this->belongsTo(OrgUnit::class, 'org_unit_id');
    }

    public function vectors(): HasMany
    {
        return $this->hasMany(Vector::class, 'document_id');
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Scope documents visible to given user according to rules:
     * - guest: Level1 only
     * - staff of a unit: their unit Level1-3, other units Level1-2
     * - non-staff authenticated: Level1 only
     */
    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function visibleTo($query, ?User $user)
    {
        if (! $user instanceof \App\Models\User) {
            return $query->where('classification_level', 1);
        }

        $isStaff = $user->is_staff ?? false;
        $userOrg = $user->org_unit_id ?? null;

        if ($isStaff && $userOrg !== null) {
            return $query->where(function ($q) use ($userOrg): void {
                $q->where(function ($q2) use ($userOrg): void {
                    $q2->where('org_unit_id', $userOrg)
                        ->where('classification_level', '<=', 3);
                })->orWhere(function ($q3) use ($userOrg): void {
                    $q3->where('org_unit_id', '!=', $userOrg)
                        ->where('classification_level', '<=', 2);
                });
            });
        }

        return $query->where('classification_level', 1);
    }

    public function canBeViewedBy(?User $user): bool
    {
        return (bool) self::query()->where('id', $this->id)->visibleTo($user)->exists();
    }

    protected function casts(): array
    {
        return [
            'settings' => 'array',
        ];
    }
}

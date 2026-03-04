<?php

declare(strict_types=1);

namespace App\Models\Dms;

use Illuminate\Support\Arr;
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

    protected $guarded = [];

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
    
    public static function resolveCreation(array $record)
    {
        // ---- Normalize incoming payload ------------------------------------
        $id          = Arr::get($record, 'id');       // may be null
        $code        = Arr::get($record, 'code');     // unique
        $title       = Arr::get($record, 'title');
        $description = Arr::get($record, 'description');
        $orgUnitId   = Arr::get($record, 'org_unit_id');
        $ownerId     = Arr::get($record, 'owner_staff_id'); // optional
        $status      = Arr::get($record, 'status');   // optional
        $publishedAt = Arr::get($record, 'published_at');
        $effectiveAt = Arr::get($record, 'effective_at');
        $retiredAt   = Arr::get($record, 'retired_at');

        // ---- Resolve classification (string -> classification_id) ----------
        $classificationName = trim((string) Arr::get($record, 'classification', ''));
        $classificationId   = null;

        if ($classificationName !== '') {
            $classification = DocumentClassification::firstOrCreate(
                ['name' => $classificationName],
                ['description' => null] // or map a description if you add it in JSON
            );
            $classificationId = $classification->getKey();
        }

        // ---- Resolve document type (string -> document_type_id) -----------
        // NOTE: per your migration, document_type_id references d_document_levels
        $docTypeName = trim((string) Arr::get($record, 'document_type', ''));
        $documentTypeId = null;

        if ($docTypeName !== '') {
            // Default level to 'internal' unless you pass 'level' in JSON
            $docLevelValue = Arr::get($record, 'level', 'internal'); 
            $docType = DocumentLevel::firstOrCreate(
                ['name' => $docTypeName],
                [
                    // enum: 'public', 'internal', 'confidential', 'strictly_confidential'
                    'level' => in_array($docLevelValue, ['public','internal','confidential','strictly_confidential'], true)
                        ? $docLevelValue
                        : 'internal',
                    'description' => null,
                ]
            );
            $documentTypeId = $docType->getKey();
        }

        // ---- Resolve parent (string code or numeric id -> parent_id) -------
        $parentId = null;
        $parentKey = Arr::get($record, 'parent');

        if (!is_null($parentKey) && $parentKey !== '') {
            $parent = is_numeric($parentKey)
                ? static::query()->whereKey($parentKey)->first()
                : static::query()->where('code', $parentKey)->first();

            if ($parent) {
                $parentId = $parent->getKey();
            } else {
                // Parent may appear later in the JSON; you can run a second pass to backfill if needed.
                echo "⚠️  Parent '{$parentKey}' not found; leaving parent_id = null for document code '{$code}'.\n";
            }
        }

        // ---- Build attributes ----------------------------------------------
        $attributes = [
            'code'               => $code, // keep code consistent with unique index
            'title'              => $title,
            'description'        => $description,
            'org_unit_id'        => $orgUnitId,
            'owner_staff_id'     => $ownerId,
            'classification_id'  => $classificationId,
            'document_type_id'   => $documentTypeId,
            'parent_id'          => $parentId,
        ];

        // Optional timestamps/status if you include them in JSON
        if (!is_null($status))      { $attributes['status'] = $status; }
        if (!is_null($publishedAt)) { $attributes['published_at'] = $publishedAt; }
        if (!is_null($effectiveAt)) { $attributes['effective_at'] = $effectiveAt; }
        if (!is_null($retiredAt))   { $attributes['retired_at'] = $retiredAt; }

        // ---- Upsert by id (if provided), else by unique code ---------------
        // Using updateOrCreate ensures id is respected on create, and code uniqueness is preserved.
        $where = $id ? ['id' => $id] : ['code' => $code];
        $document = static::query()->updateOrCreate($where, $attributes);
        
        return $document;
    }

}

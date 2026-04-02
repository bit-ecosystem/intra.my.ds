<?php

declare(strict_types=1);

namespace Bites\Core\Organization\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\UseResource;
 
#[UseResource(\Bites\Core\Resources\LocationResource::class)]
class Location extends Model
{
    // protected $table = 'locations';

    protected $guarded = [];

    public function parent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        // Adding with('parent') makes it recursive
        return $this->belongsTo(Location::class, 'parent_id')->with('parent');
    }

    public function children(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Location::class, 'parent_id');
    }
    
    public function company(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get only "Last Mile" locations (records with no children)
     */
    public static function getLastMileOptions(): array
    {
        return self::query()
            ->doesntHave('children')
            ->get()
            ->mapWithKeys(fn($location): array => [$location->id => $location->full_path])
            ->toArray();
    }

    /**
     * Accessor to get the full breadcrumb path
     */
    protected function fullPath(): Attribute
    {
        return Attribute::make(get: function () {
            $path = collect([$this->name]);
            $current = $this->parent;
            while ($current) {
                $path->prepend($current->name);
                $current = $current->parent;
            }

            return $path->implode(' > ');
        });
    }
}

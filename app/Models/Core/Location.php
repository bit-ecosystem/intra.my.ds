<?php

declare(strict_types=1);

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    // protected $table = 'locations';

    protected $guarded = [];

    public function parent()
    {
        // Adding with('parent') makes it recursive
        return $this->belongsTo(Location::class, 'parent_id')->with('parent');
    }

    public function children()
    {
        return $this->hasMany(Location::class, 'parent_id');
    }

    /**
     * Get only "Last Mile" locations (records with no children)
     */
    public static function getLastMileOptions(): array
    {
        return self::query()
            ->doesntHave('children')
            ->get()
            ->mapWithKeys(fn ($location): array => [$location->id => $location->full_path])
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

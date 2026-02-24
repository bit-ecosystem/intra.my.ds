<?php

declare(strict_types=1);

namespace Bites\Attachables\Models\Concerns;

use Bites\Attachables\Models\PeopleAttribute;
use Bites\Attachables\Models\WorkShift;

trait HasPeopleAttributes
{
    /**
     * Polymorphic many-to-many relation to PeopleAttribute.
     */
    public function peopleAttributes()
    {
        return $this->morphToMany(PeopleAttribute::class, 'attributeable');
    }

    /**
     * Attach a new attribute to this model.
     */
    public function addAttribute(string $key, string $value)
    {
        $attr = PeopleAttribute::firstOrCreate([
            'key' => $key,
            'value' => $value,
        ]);

        $this->peopleAttributes()->syncWithoutDetaching([$attr->id]);

        return $attr;
    }

    /**
     * Get attribute value by key.
     */
    public function getAttributeValue(string $key)
    {
        return optional(
            $this->peopleAttributes()->where('key', $key)->first()
        )->value;
    }

    /**
     * Relation to WorkShift (optional).
     */
    public function workShift()
    {
        return $this->belongsTo(WorkShift::class);
    }

    /**
     * Assign a work shift to this model.
     */
    public function assignWorkShift(string $group, string $pattern)
    {
        $shift = WorkShift::firstOrCreate([
            'group' => $group,
            'pattern' => $pattern,
        ]);

        $this->workShift()->associate($shift)->save();

        return $shift;
    }
}

<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\EventType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Event extends Model
{
    protected $table = 'events';

    protected $fillable = [
        'org_unit_id',
        'owner_id',
        'title',
        'description',
        'is_all_day',
        'starts_at',
        'ends_at',
        'timezone',
        'type',
        'status',
        'color',
        'start_UTC',
        'end_UTC',

    ];

    // Keep start_UTC/end_UTC aligned when saving
    protected static function booted()
    {
        static::saving(function (self $event): void {
            if ($event->starts_at) {
                $event->start_UTC = $event->starts_at->copy()->timezone('UTC')->toDateString();
            }
            $event->end_UTC = $event->ends_at ? $event->ends_at->copy()->timezone('UTC')->toDateString() : $event->start_UTC;
        });
    }

    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    public function eventable()
    {
        return $this->morphTo();
    }

    protected function casts(): array
    {
        return [
            'is_all_day' => 'boolean',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'start_UTC' => 'datetime',
            'end_UTC' => 'datetime',
            'meta' => 'array',
        ];
    }

    /**
     * Create or update an Event from a $record array.
     *
     * Behavior:
     * - If 'id' is provided, update that row; else create a new one.
     * - If 'type' matches EventType (case-insensitive), canonicalize it and set 'color' to OKLCH.
     * - Only persist whitelisted fields (fillable).
     * - Optionally attach roles if provided and method exists.
     */
    public static function resolveCreation(array $record)
    {
        // 1) Normalize booleans and trim strings (cheap hygiene)
        if (array_key_exists('is_active', $record)) {
            $record['is_active'] = (bool) $record['is_active'];
        }

        // 2) Resolve and canonicalize type → set OKLCH color
        if (! empty($record['type']) && is_string($record['type'])) {
            $eventType = EventType::tryFrom($record['type'])
                ?? collect(EventType::cases())
                    ->first(fn (EventType $c) => mb_strtolower($c->value) === mb_strtolower($record['type']));

            if ($eventType instanceof EventType) {
                // Canonical label
                $record['type'] = $eventType->value;

                // Choose shade if you like; 500 is a good mid-tone
                $oklch = $eventType->getColor()[400];
                if (is_string($oklch)) {
                    $record['color'] = $oklch;
                }
            }
        }
        // 3) Keep only fields we want to persist
        $data = Arr::only($record, [
            'title',
            'description',
            'org_unit_id',
            'is_all_day',
            'status',
            'type',
            'color',
            'starts_at',
            'ends_at',
            'timezone',
            'owner_id',
            'start_UTC',
            'end_UTC',
        ]);

        // Don't overwrite with nulls unless explicitly intended
        $data = array_filter(
            $data,
            static fn ($v) => ! is_null($v)
        );

        // 4) Create or update
        $event = null;
        if (! empty($record['id'])) {
            // Update by id if present
            $event = self::query()->find($record['id']);
            if ($event) {
                $event->fill($data)->save();
            } else {
                // If id is provided but not found, fall back to create
                $event = self::query()->create($data);
            }
        } else {
            // Create new
            $event = self::query()->create($data);
        }

        // 5) Attach/sync roles if provided (optional & safe)
        if (! empty($record['roles']) && method_exists($event, 'attachRolesFromMixed')) {
            $event->attachRolesFromMixed($record['roles'], [
                'sync_per_team' => false,
            ]);
        }

        return $event->refresh();
    }
}

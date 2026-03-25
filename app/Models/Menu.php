<?php

declare(strict_types=1);

namespace App\Models;

use Bites\Shared\Concerns\HasAttachableRoles;
use Bites\Attachables\Models\Concerns\HasAttachableExtLink;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasAttachableExtLink;
    use HasAttachableRoles;

    protected $guard_name = 'web';

    protected $fillable = [
        'category',
        'title',
        'icon',
        'icon_type',
        'description',
        'internal_link',
        // 'external_link',
        'is_active',
    ];

    protected $attributes = [
        'is_active' => false,
    ];

    /**
     * Create/update a Menu and attach roles from $record['roles'].
     *
     * Supported formats for $record['roles']:
     *  - ['admin', 'viewer']                          // role names
     *  - [1, 2, 3]                                    // role IDs
     *  - [['name' => 'admin', 'team_id' => 10], ...]  // name + optional team_id
     *  - [['id' => 5, 'team_id' => null], ...]        // id + optional team_id
     *
     * @return static
     */
    public static function resolveCreation(array $record)
    {
        // 1) Create or update the base menu
        $menu = self::updateOrCreate(
            ['id' => $record['id'] ?? null],
            [
                'category' => $record['category'] ?? null,
                'title' => $record['title'] ?? null,
                'description' => $record['description'] ?? null,
                'icon' => $record['icon'] ?? null,
                'internal_link' => $record['internal_link'] ?? null,
                // 'external_link' => $record['external_link'] ?? null,
                'is_active' => (bool) ($record['is_active'] ?? false),
            ]
        );

        // 2) Attach roles if provided
        if (! empty($record['roles'])) {
            $menu->attachRolesFromMixed($record['roles'], [
                'sync_per_team' => false, // set true if you want replace-per-team behavior
            ]);
        }

        if (array_key_exists('external_link', $record) && $record['external_link'] !== null && $record['external_link'] !== '') {
            $menu->setLink($record['external_link']);
        }

        return $menu->refresh();
    }
}

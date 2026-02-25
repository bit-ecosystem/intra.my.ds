<?php

declare(strict_types=1);

namespace App\Enums;

enum LocationType: string
{
    case Enterprise = 'Enterprise';
    case Site = 'Site';
    case Area = 'Area';
    case WorkCenter = 'Work Center';
    case WorkUnit = 'Work Unit';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Enterprise => 'Enterprises',
            self::Site => 'Sites',
            self::Area => 'Areas',
            self::WorkCenter => 'Work Centers',
            self::WorkUnit => 'Work Units',
        };
    }
}

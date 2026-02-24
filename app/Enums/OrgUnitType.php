<?php

declare(strict_types=1);

namespace App\Enums;

enum OrgUnitType: string
{
    case Division = 'Division';
    case Department = 'Department';
    case Team = 'Team';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Division => 'Divisions',
            self::Department => 'Departments',
            self::Team => 'Teams',
        };
    }
}

<?php

declare(strict_types=1);

namespace App\Enums;

use Illuminate\Contracts\Support\Htmlable;

enum MenuCategory: string
{
    case CnB = 'C&B';
    case LnD = 'L&D';
    case Ideas = 'Ideas';
    case Profile = 'Profile';
    case Availability = 'Availability';
    case Support = 'Support';
    case Knowledge = 'Knowledge';
    case Performance = 'Performance';
    case News = 'News';
    case Lifecycle = 'Lifecycle';
    case Team = 'Team';

    public function getLabel(): string|Htmlable|null
    {
        return $this->value;
    }
}

<?php

declare(strict_types=1);

namespace App\Enums;

enum DocClass: string
{
    case L1 = 'L1';
    case L2 = 'L2';
    case L3 = 'L3';
    case L4 = 'L4';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::L1 => 'Public',
            self::L2 => 'General',
            self::L3 => 'Confidential',
            self::L4 => 'Highly Confidential',
        };
    }
}

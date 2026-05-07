<?php

declare(strict_types=1);

namespace Bites\Platform\Auth\Entities;

use LdapRecord\Models\Model;

class User extends Model
{
    public static array $objectClasses = [
        'top',
        'person',
        'organizationalperson',
        'user',
    ];
}

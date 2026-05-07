<?php

declare(strict_types=1);

namespace Bites\Platform\Auth\Entities;

use Illuminate\Database\Eloquent\Attributes\Connection;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Connection('sqlite')]
#[Table('external_identities')]
class ExternalIdentity extends Model
{
    //
}

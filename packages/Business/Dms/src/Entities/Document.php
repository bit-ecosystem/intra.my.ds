<?php

declare(strict_types=1);

namespace Bites\Business\Dms\Entities;

use Illuminate\Database\Eloquent\Attributes\Connection;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Connection('sqlite')]
#[Table('documents')]
class Document extends Model
{
    //
}

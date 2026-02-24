<?php

declare(strict_types=1);

namespace Bites\WeaverSSO\Models;

use Illuminate\Database\Eloquent\Model;

class WeaverAccount extends Model
{
    protected $fillable = ['user_id', 'weaver_login'];
}

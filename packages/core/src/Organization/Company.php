<?php

declare(strict_types=1);

namespace Bites\Core\Organization;

use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Database\Eloquent\Model;

#[UseResource(CompanyJsonApi::class)]
class Company extends Model
{
    protected $table = 'companies';

    protected $guarded = [];
}

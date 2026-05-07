<?php

declare(strict_types=1);

namespace Bites\Business\Eam\Entities;

use Illuminate\Database\Eloquent\Attributes\Connection;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Connection('sqlite')]
#[Table('asset_maintenances')]
class AssetMaintenance extends Model
{
    //
}

<?php

declare(strict_types=1);

namespace Bites\Kbm\Dms\Models;

use Illuminate\Database\Eloquent\Model;
use Bites\Shared\Concerns\HasAttachableExtLink;

class Attachment extends Model
{
    use HasAttachableExtLink;
    
    protected $table = 'd_attachments';

    protected $guarded = [];
}

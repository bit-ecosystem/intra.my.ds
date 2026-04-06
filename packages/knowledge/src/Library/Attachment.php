<?php

declare(strict_types=1);

namespace Bites\Knowledge\Library;

use Bites\Shared\Concerns\HasAttachableExtLink;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasAttachableExtLink;

    protected $table = 'd_attachments';

    protected $guarded = [];
}

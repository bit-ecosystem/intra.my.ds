<?php

declare(strict_types=1);

namespace Bites\Base\Workflow;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transition extends Model
{
    protected $table = 'w_transitions';

    protected $guarded = [];

    public function workflow(): BelongsTo
    {
        return $this->belongsTo(Workflow::class, 'workflow_id');
    }

    public function from(): BelongsTo
    {
        return $this->belongsTo(Node::class, 'from_state_id');
    }

    public function to(): BelongsTo
    {
        return $this->belongsTo(Node::class, 'to_state_id');
    }
}

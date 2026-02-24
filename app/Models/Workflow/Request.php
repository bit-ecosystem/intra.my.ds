<?php

declare(strict_types=1);

namespace App\Models\Workflow;

use App\Models\Core\OrgRole;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Request extends Model
{
    protected $table = 'w_requests';

    protected $guarded = [];

    public function workflow(): BelongsTo
    {
        return $this->belongsTo(Workflow::class, 'workflow_id');
    }

    public function currentState(): BelongsTo
    {
        return $this->belongsTo(Node::class, 'current_state_id');
    }

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    public function initiator(): BelongsTo
    {
        return $this->belongsTo(OrgRole::class, 'initiator_id');
    }
}

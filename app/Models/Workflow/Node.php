<?php

declare(strict_types=1);

namespace App\Models\Workflow;

use App\Models\Concerns\HasAttachableRoles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Models\Role;

class Node extends Model
{
    use HasAttachableRoles;

    protected $table = 'w_nodes';

    protected $guarded = [];

    public function workflow(): BelongsTo
    {
        return $this->belongsTo(Workflow::class, 'workflow_id');
    }

    public function assigneeRole(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'assignee_role_id');
    }

    public function outgoingTransitions(): HasMany
    {
        return $this->hasMany(Transition::class, 'from_state_id');
    }

    public function incomingTransitions(): HasMany
    {
        return $this->hasMany(Transition::class, 'to_state_id');
    }

    public function requestsAsCurrent(): HasMany
    {
        return $this->hasMany(Request::class, 'current_state_id');
    }

    protected function casts(): array
    {
        return [
            'is_initial' => 'boolean',
            'is_final' => 'boolean',
        ];
    }
}

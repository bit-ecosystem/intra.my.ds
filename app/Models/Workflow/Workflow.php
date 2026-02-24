<?php

declare(strict_types=1);

namespace App\Models\Workflow;

use App\Models\Core\OrgUnit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Workflow extends Model
{
    protected $table = 'w_workflows';

    protected $guarded = [];

    public function turtle(): BelongsTo
    {
        return $this->belongsTo(Turtle::class, 'turtle_id');
    }

    public function nodes(): HasMany
    {
        return $this->hasMany(Node::class, 'workflow_id')->orderBy('sort');
    }

    public function transitions(): HasMany
    {
        return $this->hasMany(Transition::class, 'workflow_id')->orderBy('sort');
    }

    public function workflowables(): HasMany
    {
        return $this->hasMany(Workflowable::class, 'workflow_id');
    }

    public function requests(): HasMany
    {
        return $this->hasMany(Request::class, 'workflow_id');
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class, 'workflow_id');
    }

    public static function resolveCreation(array $data): self
    {
        $data['org_unit_id'] = empty($data['org_unit_name']) ? null : OrgUnit::firstOrCreate(['name' => $data['org_unit_name']], ['description' => 'created from Workflow:'.$data['code'].' - '.$data['name']])->id;
        $data['turtle_id'] = $data['turtle_id']
            ?? (empty($data['turtle_name'])
                ? null
                : Turtle::firstOrCreate(
                    ['name' => $data['turtle_name']],
                    ['description' => 'created from Workflow:'.$data['code'].' - '.$data['name']]
                )->id);
        if (empty($data['state'])) {
            $data['state'] = empty($data['external_link'])
                ? 'inactive'
                : 'external_workflow';
        }

        unset($data['org_unit_name'], $data['turtle_name']);  // Create Workflow

        return self::create($data);
    }
}

<?php

declare(strict_types=1);

namespace App\Models\Workflow;

use App\Models\Core\OrgRole;
use App\Models\Core\OrgUnit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Turtle extends Model
{
    protected $table = 'w_turtles';

    protected $guarded = [];

    public function orgUnit(): BelongsTo
    {
        return $this->belongsTo(OrgUnit::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(OrgRole::class, 'supplier_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(OrgRole::class, 'customer_id');
    }

    public function orgRole(): BelongsTo
    {
        return $this->belongsTo(OrgRole::class, 'org_role_id');
    }

    public function workflows(): HasMany
    {
        return $this->hasMany(Workflow::class, 'turtle_id');
    }

    public static function resolveCreation(array $data): self
    {
        $data['org_unit_id'] = empty($data['org_unit_name']) ? null : OrgUnit::firstOrCreate(['name' => $data['org_unit_name']], ['description' => 'created from Turtle:'.$data['code'].' - '.$data['name']])->id;  // Create Turtle

        return self::create($data);
    }
}

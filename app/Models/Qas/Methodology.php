<?php

declare(strict_types=1);

namespace App\Models\Qas;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Methodology extends Model
{
    protected $table = 'q_methodologies';

    protected $fillable = [
        'methodology',
        'purpose',
        'brief_explanation',
        'needs_form',
        'needs_report',
        'typical_record_type',
        'example_template_name',
        'external_url',
    ];

    public function runs(): HasMany
    {
        return $this->hasMany(RunInitiative::class, 'methodology_id');
    }
}

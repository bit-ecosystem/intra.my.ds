<?php

declare(strict_types=1);

namespace App\Models\Qas;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Methodology extends Model
{
    protected $table = 'q_methodologies';
    
    protected $guarded = ['id', 'created_at', 'updated_at'];
    
    protected $casts = [
        'form_schema'   => 'array',
        'report_schema' => 'array',
        'needs_form'    => 'boolean',
        'needs_report'  => 'boolean',
    ];


    public function runs(): HasMany
    {
        return $this->hasMany(RunInitiative::class, 'methodology_id');
    }
}

<?php

declare(strict_types=1);

namespace App\Models\Lms;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Material extends Model
{
    use HasFactory;

    protected $table = 'l_materials';

    protected $fillable = [
        'module_id',
        'title',
        'type',
        'url',
        'order_index',
        'meta',
    ];
    protected $casts = ['meta' => 'array'];

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class, 'module_id');
    }

    protected function casts(): array
    {
        return [
            'meta' => 'array',
            'order_index' => 'integer',
        ];
    }


    public function modules(): BelongsToMany
    {
        return $this->belongsToMany(Module::class, 'l_material_module')
            ->withPivot(['order_index'])
            ->withTimestamps();
    }
}

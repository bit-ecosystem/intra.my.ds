<?php

declare(strict_types=1);

namespace App\Models\Lms;

use App\Enums\CourseGroup;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $table = 'l_courses';

    protected $fillable = [
        'code',
        'title',
        'description',
        'category',
        'status',
        'published_at',
    ];

    protected $casts = [
        'category' => CourseGroup::class,
    ];

    /**
     * 1:N path (because l_modules has course_id).
     */
    public function modules()
    {
        return $this->belongsToMany(Module::class, 'l_course_module', 'course_id', 'module_id')
            ->withPivot(['order_index'])
            ->withTimestamps();
    }
}

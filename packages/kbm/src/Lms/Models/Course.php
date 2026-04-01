<?php

declare(strict_types=1);

namespace Bites\Kbm\Lms\Models;

use App\Enums\CourseGroup;
use Bites\Kbm\Factories\CourseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Model;

#[UseFactory(CourseFactory::class)]
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

    /**
     * 1:N path (because l_modules has course_id).
     */
    public function modules()
    {
        return $this->belongsToMany(Module::class, 'l_course_module', 'course_id', 'module_id')
            ->withPivot(['order_index'])
            ->withTimestamps();
    }
    
    protected function casts(): array
    {
        return [
            'category' => CourseGroup::class,
        ];
    }
}

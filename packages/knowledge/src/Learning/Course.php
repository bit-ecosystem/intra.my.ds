<?php

declare(strict_types=1);

namespace Bites\Knowledge\Learning;

use App\Enums\CourseGroup;
use Bites\Shared\Concerns\HasStakeHolder;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[UseResource(CourseJsonApi::class)]
#[UseFactory(CourseFactory::class)]
#[UsePolicy(CoursePolicy::class)]
class Course extends Model
{
    use HasFactory, HasStakeHolder;

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

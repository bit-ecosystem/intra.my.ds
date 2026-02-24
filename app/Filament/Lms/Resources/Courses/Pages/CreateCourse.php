<?php

declare(strict_types=1);

namespace App\Filament\Lms\Resources\Courses\Pages;

use App\Filament\Lms\Resources\Courses\CourseResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCourse extends CreateRecord
{
    protected static string $resource = CourseResource::class;
}

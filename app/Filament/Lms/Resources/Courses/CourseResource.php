<?php

declare(strict_types=1);

namespace App\Filament\Lms\Resources\Courses;

use App\Filament\Lms\Resources\Courses\Pages\CreateCourse;
use App\Filament\Lms\Resources\Courses\Pages\EditCourse;
use App\Filament\Lms\Resources\Courses\Pages\ListCourses;
use App\Filament\Lms\Resources\Courses\Pages\ViewCourse;
use App\Filament\Lms\Resources\Courses\Schemas\CourseForm;
use App\Filament\Lms\Resources\Courses\Schemas\CourseInfolist;
use App\Filament\Lms\Resources\Courses\Tables\CoursesTable;
use BackedEnum;
use Bites\Business\Lms\Entities\Course;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static string|BackedEnum|null $navigationIcon = 'myicon-course';

    protected static string|UnitEnum|null $navigationGroup = 'Classroom';

    protected static ?string $modelLabel = 'Courses';

    protected static ?int $navigationSort = 3;

    // public static function getEloquentQuery(): Builder
    // {
    //     // return static::getModel()::query(); // ✅ ensure not null
    //         return parent::getEloquentQuery();
    // }

    public static function form(Schema $schema): Schema
    {
        return CourseForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CourseInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CoursesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ModulesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCourses::route('/'),
            'create' => CreateCourse::route('/create'),
            'view' => ViewCourse::route('/{record}'),
            'edit' => EditCourse::route('/{record}/edit'),
        ];
    }
}

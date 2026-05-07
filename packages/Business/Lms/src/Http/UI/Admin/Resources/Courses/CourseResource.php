<?php

declare(strict_types=1);

namespace Bites\Business\Lms\Http\UI\Admin\Resources\Courses;

use BackedEnum;
use Bites\Business\Lms\Entities\Course;
use Bites\Business\Lms\Http\UI\Admin\Resources\Courses\Pages\CreateCourse;
use Bites\Business\Lms\Http\UI\Admin\Resources\Courses\Pages\EditCourse;
use Bites\Business\Lms\Http\UI\Admin\Resources\Courses\Pages\ListCourses;
use Bites\Business\Lms\Http\UI\Admin\Resources\Courses\Pages\ViewCourse;
use Bites\Business\Lms\Http\UI\Admin\Resources\Courses\Schemas\CourseForm;
use Bites\Business\Lms\Http\UI\Admin\Resources\Courses\Schemas\CourseInfolist;
use Bites\Business\Lms\Http\UI\Admin\Resources\Courses\Tables\CoursesTable;
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

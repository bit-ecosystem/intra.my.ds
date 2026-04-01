<?php

namespace Database\Seeders;

use Bites\Kbm\Lms\Models\Course;
use Bites\Kbm\Lms\Models\Module;
use Bites\Kbm\Lms\Models\Quiz;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class QuizSeeder extends Seeder
{
    public function run(): void
    {
        $path = base_path('database/factories/Lms/quiz.json');
        $quizzes = json_decode(file_get_contents($path), true);

        if (Arr::isAssoc($quizzes)) {
            $quizzes = [$quizzes];
        }

        foreach ($quizzes as $quiz) {
            $this->insertQuiz($quiz);
        }
        echo "✅ Seeded LMS quizzes data\n";
    }

    private function insertQuiz(array $quiz): void
    {
        $courseTitle = $quiz['course'] ?? null;
        $moduleTitle = $quiz['module'] ?? null;

        if (! $courseTitle || ! $moduleTitle) {
            return;
        }

        // ✅ Find or create course using factory
        $course = Course::where('title', $courseTitle)->first();
        if (! $course) {
            $course = Course::factory()->create([
                'title' => $courseTitle,
                'code' => strtoupper(str_replace(' ', '-', $courseTitle)).'-'.uniqid(),
                'category' => 'Digital',
                'status' => 'published',
                'published_at' => now(),
            ]);
        }

        // ✅ Find or create module using factory
        $module = Module::where('title', $moduleTitle)->first();
        if (! $module) {
            $module = Module::factory()->create([
                'title' => $moduleTitle,
                'slug' => Str::slug($moduleTitle),
                'order_index' => 0,
            ]);
        }

        // ✅ Attach module to course if not already attached
        if (! $course->modules()->where('module_id', $module->id)->exists()) {
            $course->modules()->attach($module->id, ['order_index' => $module->order_index]);
        }

        $schemaPayload = $quiz['schema'] ?? null;

        if (is_array($schemaPayload)) {
            $schemaJson = json_encode($schemaPayload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        } elseif (is_string($schemaPayload)) {
            // If schema is already a JSON string, you can trust it or re-decode/encode
            $schemaJson = $schemaPayload;
        } else {
            $schemaJson = null;
        }

        Quiz::updateOrCreate(
            ['code' => strtoupper($quiz['name'])],
            [
                'module_id' => $module->id,
                'name' => $quiz['name'] ?? 'Untitled Quiz',
                'passing_mark' => $quiz['passing_mark'] ?? null,
                'is_active' => true,
                'schema' => is_array($quiz['schema']) ? $quiz['schema'] : (is_string($quiz['schema']) ? json_decode($quiz['schema'], true) : []),
            ]

        );
    }
}

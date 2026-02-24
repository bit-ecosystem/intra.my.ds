<?php

namespace Database\Seeders;

use App\Models\Lms\Course;
use App\Models\Lms\Material;
use App\Models\Lms\Module;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class LmsSeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = base_path('database/factories/Lms/course.json');

        if (! File::exists($jsonPath)) {
            $this->command->error("JSON file not found at: $jsonPath");

            return;
        }

        $data = json_decode(File::get($jsonPath), true);

        foreach ($data as $courseData) {
            // Create Course
            $course = Course::firstOrCreate(
                ['title' => $courseData['course']],
                [
                    'code' => strtoupper(str_replace(' ', '_', $courseData['course'])),
                    'description' => $courseData['description'] ?? '',
                    'category' => $courseData['category'] ?? 'factory',
                    'status' => 'draft',
                    'published_at' => now(),
                ]
            );

            // Loop through modules
            foreach ($courseData['modules'] as $index => $moduleData) {

                if (method_exists(Module::class, 'resolveCreation')) {
                    $module = Module::resolveCreation($moduleData, $index);
                } else {
                    $module = Module::firstOrCreate(['id' => $moduleData['id']], $moduleData);
                }

                // $module = Module::firstOrCreate(
                //     ['title' => $moduleData['name']],
                //     [
                //         'slug' => Str::slug($moduleData['name']),
                //         'description' => $moduleData['description'] ?? '',
                //         'order_index' => $index + 1,
                //         'estimated_duration_minutes' => 60, // default
                //         'validity_months' => 12, // default
                //         'certificate_template' => [],
                //     ]
                // );

                // Attach module to course via pivot
                $course->modules()->syncWithoutDetaching([
                    $module->id => ['order_index' => $index + 1],
                ]);

                // Loop through materials
                if (! empty($moduleData['materials'])) {
                    foreach ($moduleData['materials'] as $mIndex => $materialData) {
                        Material::firstOrCreate(
                            [
                                'module_id' => $module->id,
                                'title' => $materialData['title'],
                            ],
                            [
                                'type' => $materialData['type'] ?? 'pdf',
                                'url' => $materialData['url'] ?? '',
                                'order_index' => $mIndex + 1,
                                'meta' => [],
                            ]
                        );
                    }
                }
            }
        }
        echo "✅ Seeded LMS data\n";
    }
}

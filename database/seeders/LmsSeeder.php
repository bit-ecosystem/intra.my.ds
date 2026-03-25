<?php

namespace Database\Seeders;

use Bites\Kbm\Lms\Models\Course;
use Bites\Kbm\Lms\Models\Material;
use Bites\Kbm\Lms\Models\Module;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class LmsSeeder extends Seeder
{
    public function run(): void
    {
        // Seed from course.json (Course -> Modules -> Materials)
        $this->seedFromCoursesJson(base_path('database/factories/Lms/course.json'));

        // Seed from module.json (Modules -> Materials only, no courses)
        $this->seedFromModulesJson(base_path('database/factories/Lms/module.json'));

        $this->command?->info('✅ LMS seeding complete.');
    }

    /**
     * Seed courses with nested modules + materials.
     */
    protected function seedFromCoursesJson(string $jsonPath): void
    {
        if (! File::exists($jsonPath)) {
            $this->command?->warn("Skipping courses: JSON not found at $jsonPath");

            return;
        }

        $data = json_decode(File::get($jsonPath), true);
        if (! is_array($data)) {
            $this->command?->error("Invalid courses JSON at $jsonPath");

            return;
        }

        foreach ($data as $courseData) {
            if (! is_array($courseData)) {
                // Allow a simple string course title
                $courseData = ['course' => (string) $courseData];
            }

            // --- Create Course ---
            $courseTitle = $courseData['course'] ?? 'Untitled Course';
            $course = Course::firstOrCreate(
                ['title' => $courseTitle],
                [
                    'code' => strtoupper(str_replace(' ', '_', $courseTitle)),
                    'description' => $courseData['description'] ?? '',
                    'category' => $courseData['category'] ?? 'factory',
                    'status' => 'draft',
                    'published_at' => now(),
                ]
            );

            // --- Modules ---
            $modules = $this->normalizeModules($courseData['modules'] ?? []);
            $moduleOrder = 0;

            foreach ($modules as $moduleData) {
                $moduleOrder++;

                // If you have custom creation logic
                if (method_exists(Module::class, 'resolveCreation')) {
                    /** @var Module $module */
                    $module = Module::resolveCreation($moduleData, $moduleOrder - 1);
                } else {
                    $name = $moduleData['name'] ?? ('Module '.$moduleOrder);
                    $slug = $moduleData['slug'] ?? Str::slug($name);

                    $module = Module::firstOrCreate(
                        ['slug' => $slug],
                        [
                            'title' => $name,
                            'description' => $moduleData['description'] ?? '',
                            'order_index' => $moduleData['order_index'] ?? $moduleOrder,
                            'estimated_duration_minutes' => $moduleData['estimated_duration_minutes'] ?? 60,
                            'validity_months' => $moduleData['validity_months'] ?? 12,
                            'certificate_template' => $moduleData['certificate_template'] ?? [],
                        ]
                    );
                }

                // Link module to course with order (pivot)
                $course->modules()->syncWithoutDetaching([
                    $module->id => ['order_index' => $moduleOrder],
                ]);

                // --- Materials ---
                $materials = $this->normalizeMaterials($moduleData['materials'] ?? []);
                $materialOrder = 0;

                foreach ($materials as $materialData) {
                    $materialOrder++;

                    $title = $materialData['title'] ?? ('Material '.$materialOrder);
                    $url = $materialData['url'] ?? '';
                    $type = $materialData['type'] ?? 'other';
                    $meta = $materialData['meta'] ?? [];

                    // Prefer URL as unique key; fallback to title
                    $uniqueWhere = ! empty($url) ? ['url' => $url] : ['title' => $title];

                    $material = Material::firstOrCreate(
                        $uniqueWhere,
                        [
                            'title' => $title,
                            'type' => $type,
                            'url' => $url,
                            'meta' => $meta,
                        ]
                    );

                    // Attach via pivot with order_index
                    $module->materials()->syncWithoutDetaching([
                        $material->id => ['order_index' => $materialOrder],
                    ]);
                }
            }
        }

        $this->command?->info('✅ Seeded courses, modules & materials from course.json');
    }

    /**
     * Seed modules + materials only (no courses).
     */
    protected function seedFromModulesJson(string $jsonPath): void
    {
        if (! File::exists($jsonPath)) {
            $this->command?->warn("Skipping modules-only: JSON not found at $jsonPath");

            return;
        }

        $raw = json_decode(File::get($jsonPath), true);
        // Supports: [ {...} ] OR { "modules": [ {...} ] }
        $modulesRaw = $raw['modules'] ?? $raw;

        $modules = $this->normalizeModules($modulesRaw);
        $moduleOrder = 0;

        foreach ($modules as $moduleData) {
            $moduleOrder++;

            if (method_exists(Module::class, 'resolveCreation')) {
                $module = Module::resolveCreation($moduleData, $moduleOrder - 1);
            } else {
                $name = $moduleData['name'] ?? ('Module '.$moduleOrder);
                $slug = $moduleData['slug'] ?? Str::slug($name);

                $module = Module::firstOrCreate(
                    ['slug' => $slug],
                    [
                        'title' => $name,
                        'description' => $moduleData['description'] ?? '',
                        'order_index' => $moduleData['order_index'] ?? $moduleOrder,
                        'estimated_duration_minutes' => $moduleData['estimated_duration_minutes'] ?? 60,
                        'validity_months' => $moduleData['validity_months'] ?? 12,
                        'certificate_template' => $moduleData['certificate_template'] ?? [],
                    ]
                );
            }

            // Materials
            $materials = $this->normalizeMaterials($moduleData['materials'] ?? []);
            $materialOrder = 0;

            foreach ($materials as $materialData) {
                $materialOrder++;

                $title = $materialData['title'] ?? ('Material '.$materialOrder);
                $url = $materialData['url'] ?? '';
                $type = $materialData['type'] ?? 'other';
                $meta = $materialData['meta'] ?? [];

                $uniqueWhere = ! empty($url) ? ['url' => $url] : ['title' => $title];

                $material = Material::firstOrCreate(
                    $uniqueWhere,
                    [
                        'title' => $title,
                        'type' => $type,
                        'url' => $url,
                        'meta' => $meta,
                    ]
                );

                $module->materials()->syncWithoutDetaching([
                    $material->id => ['order_index' => $materialOrder],
                ]);
            }
        }

        $this->command?->info('✅ Seeded modules & materials from module.json (no courses)');
    }

    // -----------------------
    // Helpers (must be inside the class)
    // -----------------------

    /**
     * Normalize modules into a list of arrays with keys at least: name, slug.
     * Accepts:
     * - "Module A"
     * - "Module A, Module B"
     * - ["Module A", "Module B"]
     * - [{"name":"Module A"}, {"name":"Module B"}]
     * - { "modules": [...] } should be handled by caller before this method
     */
    private function normalizeModules(mixed $raw): array
    {
        if ($raw === null) {
            return [];
        }

        // Single string (possibly comma-separated)
        if (is_string($raw)) {
            $parts = str_contains($raw, ',')
                ? array_filter(array_map('trim', explode(',', $raw)))
                : [trim($raw)];

            return array_map(function ($name) {
                $name = $name === '' ? 'Module' : $name;

                return [
                    'name' => $name,
                    'slug' => Str::slug($name),
                ];
            }, $parts);
        }

        if (! is_array($raw)) {
            return [];
        }

        // Array of strings?
        if ($this->isList($raw) && isset($raw[0]) && is_string($raw[0])) {
            return array_map(function ($name) {
                $name = trim($name);
                $name = $name === '' ? 'Module' : $name;

                return [
                    'name' => $name,
                    'slug' => Str::slug($name),
                ];
            }, $raw);
        }

        // Array of arrays/objects – normalize each
        $out = [];
        foreach ($raw as $row) {
            if (is_string($row)) {
                $name = trim($row);
                $name = $name === '' ? 'Module' : $name;
                $out[] = ['name' => $name, 'slug' => Str::slug($name)];
            } elseif (is_array($row)) {
                $name = $row['name'] ?? ($row['title'] ?? null);
                if (! $name || trim($name) === '') {
                    $name = 'Module';
                }
                $slug = $row['slug'] ?? Str::slug($name);
                $row['name'] = $name;
                $row['slug'] = $slug;
                $out[] = $row;
            }
        }

        return $out;
    }

    /**
     * Detects numerically-indexed list (0..n).
     */
    private function isList(array $array): bool
    {
        if ($array === []) {
            return true;
        }

        return array_keys($array) === range(0, count($array) - 1);
    }

    /**
     * Normalize materials into a list of arrays with keys:
     *   - title (string)
     *   - type  (string, default 'other')
     *   - url   (string, default '')
     *   - meta  (array,  default [])
     *
     * Accepts:
     * - null / missing -> []
     * - "PG1" -> one item
     * - "PG1, PG2, PG3" -> split by comma
     * - ["PG1", "PG2"] -> list of strings
     * - [{"title":"PG1"}, {"title":"PG2"}] -> list of objects
     */
    private function normalizeMaterials(mixed $raw): array
    {
        // 1) Missing
        if ($raw === null) {
            return [];
        }

        // 2) Single string (may be comma-separated)
        if (is_string($raw)) {
            $parts = str_contains($raw, ',')
                ? array_filter(array_map('trim', explode(',', $raw)))
                : [trim($raw)];

            return array_map(function (string $title) {
                $title = $title === '' ? 'Untitled' : $title;

                return [
                    'title' => $title,
                    'type' => 'other',
                    'url' => '',
                    'meta' => [],
                ];
            }, $parts);
        }

        // 3) Not an array? give up gracefully
        if (! is_array($raw)) {
            return [];
        }

        $normalized = [];

        // 4) Array: could be list of strings or list of objects (or mixed)
        foreach ($raw as $row) {
            if (is_string($row)) {
                // Treat as a bare title
                $t = trim($row);
                $t = $t === '' ? 'Untitled' : $t;

                $normalized[] = [
                    'title' => $t,
                    'type' => 'other',
                    'url' => '',
                    'meta' => [],
                ];
            } elseif (is_array($row)) {
                // Object-like shape; normalize keys with defaults
                $title = isset($row['title']) && trim((string) $row['title']) !== ''
                    ? (string) $row['title']
                    : 'Untitled';

                $normalized[] = [
                    'title' => $title,
                    'type' => isset($row['type']) ? (string) $row['type'] : 'other',
                    'url' => isset($row['url']) ? (string) $row['url'] : '',
                    'meta' => isset($row['meta']) && is_array($row['meta']) ? $row['meta'] : [],
                ];
            }
            // Any other data types are ignored
        }

        return $normalized;
    }
}

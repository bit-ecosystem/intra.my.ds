<?php

namespace Database\Factories\Lms;

use App\Models\Lms\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lms\Course>
 */
class CourseFactory extends Factory
{
    protected $model = Course::class;

    public function definition(): array
    {
        $dept = strtoupper($this->faker->randomElement(['ENT', 'OPS', 'IT', 'HR', 'MFG']));
        $domain = strtoupper($this->faker->randomElement(['SEC', 'NET', 'QA', 'SM']));
        $num = $this->faker->numberBetween(100, 499);

        return [
            'code' => "{$dept}-{$domain}-{$num}",
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->optional()->paragraph(),
            'status' => $this->faker->randomElement(['draft', 'published', 'archived']),
            'published_at' => $this->faker->optional()->dateTimeBetween('-6 months', 'now'),
        ];
    }

    public function draft(): self
    {
        return $this->state(fn () => ['status' => 'draft', 'published_at' => null]);
    }

    public function published(): self
    {
        return $this->state(fn () => ['status' => 'published', 'published_at' => now()]);
    }

    public function archived(): self
    {
        return $this->state(fn () => ['status' => 'archived']);
    }
}

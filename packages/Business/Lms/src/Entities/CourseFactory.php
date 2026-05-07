<?php

declare(strict_types=1);

namespace Bites\Business\Lms\Entities;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Course>
 */
class CourseFactory extends Factory
{
    protected $model = Course::class;

    public function definition(): array
    {
        $dept = strtoupper(fake()->randomElement(['ENT', 'OPS', 'IT', 'HR', 'MFG']));
        $domain = strtoupper(fake()->randomElement(['SEC', 'NET', 'QA', 'SM']));
        $num = fake()->numberBetween(100, 499);

        return [
            'code' => sprintf('%s-%s-%d', $dept, $domain, $num),
            'title' => fake()->sentence(3),
            'description' => fake()->optional()->paragraph(),
            'status' => fake()->randomElement(['draft', 'published', 'archived']),
            'published_at' => fake()->optional()->dateTimeBetween('-6 months', 'now'),
        ];
    }

    public function draft(): self
    {
        return $this->state(fn (): array => ['status' => 'draft', 'published_at' => null]);
    }

    public function published(): self
    {
        return $this->state(fn (): array => ['status' => 'published', 'published_at' => now()]);
    }

    public function archived(): self
    {
        return $this->state(fn (): array => ['status' => 'archived']);
    }
}

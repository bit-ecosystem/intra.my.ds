<?php

declare(strict_types=1);

namespace Bites\Business\Lms\Entities;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Module>
 */
class ModuleFactory extends Factory
{
    protected $model = Module::class;

    public function definition(): array
    {
        $title = fake()->unique()->sentence(3);

        return [
            'slug' => Str::slug($title.'-'.fake()->unique()->lexify('????')),
            'title' => $title,
            'description' => fake()->optional()->paragraph(),
            'order_index' => fake()->numberBetween(0, 20),
            'estimated_duration_minutes' => fake()->optional()->numberBetween(15, 240),
            'validity_months' => fake()->optional()->numberBetween(6, 36),
            'certificate_template' => fake()->optional()->randomElement([
                ['layout' => 'default', 'theme' => 'blue', 'signature' => 'Training Dept'],
                ['layout' => 'modern', 'theme' => 'green', 'signature' => 'QA Office'],
            ]),
        ];
    }

    public function ordered(int $index = 0): self
    {
        return $this->state(fn (): array => ['order_index' => $index]);
    }
}

<?php

namespace Database\Factories\Lms;

use Bites\Business\Lms\Entities\Module;
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
        $title = $this->faker->unique()->sentence(3);

        return [
            'slug' => Str::slug($title.'-'.$this->faker->unique()->lexify('????')),
            'title' => $title,
            'description' => $this->faker->optional()->paragraph(),
            'order_index' => $this->faker->numberBetween(0, 20),
            'estimated_duration_minutes' => $this->faker->optional()->numberBetween(15, 240),
            'validity_months' => $this->faker->numberBetween(1, 36),
            'certificate_template' => $this->faker->optional()->randomElement([
                ['layout' => 'default', 'theme' => 'blue', 'signature' => 'Training Dept'],
                ['layout' => 'modern', 'theme' => 'green', 'signature' => 'QA Office'],
            ]),
        ];
    }

    public function ordered(int $index = 0): self
    {
        return $this->state(fn () => ['order_index' => $index]);
    }
}

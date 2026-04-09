<?php

namespace Database\Factories\Hrm;

use App\Models\PersonAttribute;
use App\Models\User;
use Bites\Employment\Models\Staff;
use Illuminate\Database\Eloquent\Factories\Factory;

class StaffFactory extends Factory
{
    protected $model = Staff::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'staff_number' => $this->faker->unique()->numerify('STF#####'),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Staff $staff) {
            $retireAge = (int) env('MANDATORY_RETIREMENT_AGE', 60);
            $birthDate = $this->faker->dateTimeBetween('-60 years', '-18 years');
            $joinDate = $this->faker->dateTimeBetween((clone $birthDate)->modify('+18 years'), 'now');
            $contractDuration = $this->faker->optional()->randomElement([3, 6, 12, 18, 24]); // months or null

            // Calculate end date
            if ($contractDuration) {
                // Contract: end date = join date + contract months
                $endDate = (clone $joinDate)->modify("+{$contractDuration} months");
            } else {
                // Permanent: end date = birth date + retirement age
                $endDate = (clone $birthDate)->modify("+{$retireAge} years");
            }

            // Attributes array
            $attributes = [
                ['key' => 'gender', 'value' => $this->faker->randomElement(['male', 'female'])],
                ['key' => 'birth_date', 'value' => $birthDate->format('Y-m-d')],
                ['key' => 'join_date', 'value' => $joinDate->format('Y-m-d')],
                ['key' => 'end_date', 'value' => $endDate->format('Y-m-d')],
                ['key' => 'phone', 'value' => $this->faker->phoneNumber],
            ];

            if ($contractDuration) {
                $attributes[] = ['key' => 'contract_duration', 'value' => $contractDuration]; // months
            }

            // Save attributes
            foreach ($attributes as $attr) {
                PersonAttribute::create([
                    'key' => $attr['key'],
                    'value' => $attr['value'],
                    'attributable_id' => $staff->id,
                    'attributable_type' => Staff::class,
                ]);
            }
        });
    }
}

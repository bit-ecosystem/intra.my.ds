<?php

namespace Database\Seeders;

use App\Models\Hrm\Staff;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call([CourseSeeder::class,]);
        $this->call([LmsSeeder::class]);
        $this->call([QuizSeeder::class]);
        $this->call([ModelJsonSeeder::class]);
        // User::factory()->create([
        //     'name' => 'faros',
        //     'email' => 'faros@email.com',
        // ]);
        // User::factory(10)->create();
        // Staff::factory(10)->create();

    }
}

<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $alumni = \App\Models\Alumnus::factory(10)->create();
        $courses = \App\Models\Course::factory(10)->create();
        $further_courses = \App\Models\FurtherCourse::factory(10)->create();
        $scientific_degrees = \App\Models\ScientificDegree::factory(10)->create();
        

        $alumni->each(function ($alumnus) use (&$courses, &$further_courses, &$scientific_degrees) {
            // Add course
            $alumnus->courses()->sync(
                $courses->random(rand(1,4))
            );

            // Add furthure course
            $alumnus->further_courses()->sync(
                $further_courses->random(rand(1,4))
            );

            // Add scientific degree
            $alumnus->scientific_degrees()->sync(
                $scientific_degrees->random(rand(1,4))
            );
        });

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}

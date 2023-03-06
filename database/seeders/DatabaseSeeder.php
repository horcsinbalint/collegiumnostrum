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
        $majors = \App\Models\Major::factory(10)->create();
        $further_courses = \App\Models\FurtherCourse::factory(10)->create();
        $scientific_degrees = \App\Models\ScientificDegree::factory(10)->create();
        $research_fields = \App\Models\ResearchField::factory(10)->create();


        $alumni->each(function ($alumnus) use (&$majors, &$further_courses, &$scientific_degrees, &$research_fields) {
            // Add major
            $alumnus->majors()->sync(
                $majors->random(rand(1,4))
            );

            // Add furthure course
            $alumnus->further_courses()->sync(
                $further_courses->random(rand(1,4))
            );

            // Add scientific degree
            $alumnus->scientific_degrees()->sync(
                $scientific_degrees->random(rand(1,4))
            );

            // Add research field
            $alumnus->research_fields()->sync(
                $research_fields->random(rand(1,4))
            );

        });

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}

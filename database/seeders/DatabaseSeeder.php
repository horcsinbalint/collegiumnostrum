<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $alumni = \App\Models\Alumnus::factory(30)->create();

        foreach (\App\Models\Major::$majors_enum as $major) {
            \App\Models\Major::factory()->create([
                'name' => $major,
            ]);
        }
        foreach (\App\Models\FurtherCourse::$further_courses_enum as $further_course) {
            \App\Models\FurtherCourse::factory()->create([
                'name' => $further_course,
            ]);
        }
        foreach (\App\Models\ResearchField::$research_fields_enum as $research_field) {
            \App\Models\ResearchField::factory()->create([
                'name' => $research_field,
            ]);
        }
        foreach (\App\Models\UniversityFaculty::$university_faculties_enum as $university_faculty) {
            \App\Models\UniversityFaculty::factory()->create([
                'name' => $university_faculty,
            ]);
        }
        foreach (\App\Models\ScientificDegree::$scientific_degrees_enum as $scientific_degree) {
            \App\Models\ScientificDegree::factory()->create([
                'name' => $scientific_degree,
            ]);
        }

        $majors = DB::table('majors')->pluck('id');
        $further_courses = DB::table('further_courses')->pluck('id');
        $scientific_degrees = DB::table('scientific_degrees')->pluck('id');
        $research_fields = DB::table('research_fields')->pluck('id');
        $university_faculties = DB::table('university_faculties')->pluck('id');





        $alumni->each(function ($alumnus) use (&$majors, &$further_courses, &$scientific_degrees, &$research_fields, &$university_faculties) {
            // Add major
            $alumnus->majors()->sync(
                $majors->random(rand(1,4))
            );

            // Add further course
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

            // Add universityfaculty
            $alumnus->university_faculties()->sync(
                $university_faculties->random(rand(1,4))
            );

        });

        \App\Models\User::factory()->create([
             'name' => 'Admin',
             'email' => 'root@eotvos.elte.hu',
             'is_admin' => true,
             'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // the string 'password' encrypted
        ]);
    }
}

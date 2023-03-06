<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Alumnus>
 */
class AlumnusFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'birth_date' => fake()->date('Y'),
            'birth_place' => fake()->word(),
            'high_school' => fake()->word(),
            'graduation_date' => fake()->date('Y'),
            'university_faculty' => fake()->word(),
            'further_course_detailed' => implode('\n\n',fake()->paragraphs(rand(2,8))),
            'start_of_membership' => fake()->date('Y'),
            'recognations' => fake()->sentence(5),
            'research_field_detailed' => implode('\n\n',fake()->paragraphs(rand(2,8))),
            'agreed' => true,
            'links' => fake()->url() . '\n' . fake()->url(),
            'works' => fake()->sentence(3),
        ];
    }
}

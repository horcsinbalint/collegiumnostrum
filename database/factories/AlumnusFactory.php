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
            'date_of_birth' => fake()->date('Y_m_d'),
            'place_of_birth' => fake()->word(),
            'high_school' => fake()->word(),
            'university_faculty' => fake()->word(),
            'further_course_detailed' => implode('\n\n',fake()->paragraphs(rand(2,8))),
            'start_of_membership' => fake()->date('Y'),
            'recognations' => fake()->sentence(5),
            'agreed' => true,
        ];
    }
}

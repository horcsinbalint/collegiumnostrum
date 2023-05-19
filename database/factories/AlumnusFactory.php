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
            'further_course_detailed' => fake()->paragraphs(rand(2,8), true),
            'start_of_membership' => fake()->date('Y'),
            'recognations' => fake()->sentence(5),
            'research_field_detailed' => fake()->paragraphs(rand(2,8), true),
            'agreed' => false,
            'links' => fake()->url() . '\n' . fake()->url(),
            'works' => fake()->sentence(3),
            'is_draft' => false,
        ];
    }
}

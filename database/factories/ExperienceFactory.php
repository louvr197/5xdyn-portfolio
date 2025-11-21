<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Experience>
 */
class ExperienceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $positions = [
            ['position' => 'Développeur Web', 'company' => fake()->company() . ' Tech'],
            ['position' => 'Artiste Peintre', 'company' => fake()->company() . ' Galerie'],
            ['position' => 'Développeur Full Stack', 'company' => fake()->company() . ' Digital'],
            ['position' => 'Artiste Indépendant', 'company' => 'Auto-entrepreneur'],
            ['position' => 'Sculpteur', 'company' => fake()->company() . ' Studio'],
            ['position' => 'Développeur Junior', 'company' => fake()->company() . ' Solutions'],
            ['position' => 'Professeur d\'Art', 'company' => fake()->company() . ' École d\'Art'],
            ['position' => 'Peintre', 'company' => 'Artiste Indépendant'],
        ];

        $position = fake()->randomElement($positions);
        $startDate = fake()->dateTimeBetween('-5 years', '-1 month');
        $hasEnded = fake()->boolean(60);

        return [
            'user_id' => \App\Models\User::factory(),
            'position' => $position['position'],
            'company' => $position['company'],
            'location' => fake()->city() . ', ' . fake()->country(),
            'start_date' => $startDate,
            'end_date' => $hasEnded ? fake()->dateTimeBetween($startDate, 'now') : null,
            'description' => fake()->paragraphs(2, true),
            'display_order' => fake()->numberBetween(0, 100),
            'contract_type' => fake()->randomElement(['permanent', 'fixed_term', 'internship', 'freelance', 'apprenticeship', 'volunteer']),
        ];
    }
}

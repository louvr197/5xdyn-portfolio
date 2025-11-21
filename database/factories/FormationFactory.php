<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Formation>
 */
class FormationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $degrees = [
            ['degree' => 'Licence en Informatique', 'institution' => fake()->company() . ' Université'],
            ['degree' => 'Master en Développement Web', 'institution' => fake()->company() . ' Institut'],
            ['degree' => 'Licence en Beaux-Arts', 'institution' => fake()->company() . ' École d\'Art'],
            ['degree' => 'Master en Beaux-Arts - Sculpture', 'institution' => fake()->company() . ' Académie'],
            ['degree' => 'Diplôme en Peinture Classique', 'institution' => fake()->company() . ' Atelier'],
            ['degree' => 'Certificat en Dessin de Modèle Vivant', 'institution' => fake()->company() . ' École des Arts'],
            ['degree' => 'Licence en Beaux-Arts - Peinture', 'institution' => fake()->company() . ' École Supérieure d\'Art'],
        ];

        $degree = fake()->randomElement($degrees);
        $startDate = fake()->dateTimeBetween('-10 years', '-3 years');
        $hasEnded = fake()->boolean(80);

        return [
            'user_id' => \App\Models\User::factory(),
            'degree' => $degree['degree'],
            'institution' => $degree['institution'],
            'location' => fake()->city() . ', ' . fake()->country(),
            'start_date' => $startDate,
            'end_date' => $hasEnded ? fake()->dateTimeBetween($startDate, 'now') : null,
            'description' => fake()->optional()->paragraph(),
            'display_order' => fake()->numberBetween(0, 100),
        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Information>
 */
class InformationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $titles = [
            'Développeur Full Stack',
            'Développeur Frontend',
            'Artiste Peintre',
            'Sculpteur & Artiste Visuel',
            'Développeur Créatif',
            'Artiste Traditionnel',
        ];

        return [
            'user_id' => \App\Models\User::factory(),
            'last_name' => fake()->lastName(),
            'first_name' => fake()->firstName(),
            'professional_title' => fake()->randomElement($titles),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'location' => fake()->city() . ', ' . fake()->country(),
            'availability' => fake()->randomElement(['Disponible immédiatement', 'Disponible dans 2 semaines', 'Ouvert aux opportunités', 'Actuellement en poste']),
            'bio' => fake()->paragraphs(3, true),
            'picture_path' => 'profile/' . fake()->uuid() . '.jpg',
            'cv_path' => fake()->boolean(70) ? 'cv/' . fake()->uuid() . '.pdf' : null,
            'social_links' => [
                'linkedin' => fake()->boolean(80) ? 'https://linkedin.com/in/' . fake()->userName() : null,
                'github' => fake()->boolean(70) ? 'https://github.com/' . fake()->userName() : null,
                'twitter' => fake()->boolean(50) ? 'https://twitter.com/' . fake()->userName() : null,
            ],
        ];
    }
}

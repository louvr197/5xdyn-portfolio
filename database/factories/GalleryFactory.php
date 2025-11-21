<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Gallery>
 */
class GalleryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $titles = [
            'Galerie Principale',
            'Captures d\'écran',
            'Processus de Création',
            'Résultats Finaux',
            'Esquisses & Concepts',
            'Coulisses',
            'Points Forts du Projet',
            'Travaux en Cours',
        ];

        return [
            'project_id' => \App\Models\Project::factory(),
            'title' => fake()->randomElement($titles),
        ];
    }
}

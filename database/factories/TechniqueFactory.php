<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Technique>
 */
class TechniqueFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $techniques = [
            // Art Traditionnel
            ['name' => 'Peinture à l\'Huile', 'description' => 'Techniques classiques de peinture à l\'huile et mélange des couleurs'],
            ['name' => 'Aquarelle', 'description' => 'Techniques d\'aquarelle transparente et méthodes humide sur humide'],
            ['name' => 'Peinture Acrylique', 'description' => 'Techniques acryliques à séchage rapide et superposition'],
            ['name' => 'Dessin au Fusain', 'description' => 'Esquisse expressive au fusain et ombrage'],
            ['name' => 'Dessin au Crayon', 'description' => 'Dessin détaillé au graphite et rendu réaliste'],
            ['name' => 'Sculpture en Argile', 'description' => 'Modelage à la main et sculpture en argile'],
            ['name' => 'Sculpture sur Pierre', 'description' => 'Sculpture soustractive en pierre et marbre'],
            ['name' => 'Portrait Peint', 'description' => 'Capture de la ressemblance et expression dans les portraits'],
            ['name' => 'Nature Morte', 'description' => 'Composition et observation en peinture de nature morte'],
            ['name' => 'Dessin de Figure', 'description' => 'Anatomie et gestuelle dans les études de figure'],
            ['name' => 'Composition', 'description' => 'Équilibre visuel et création de points focaux'],
            ['name' => 'Théorie des Couleurs', 'description' => 'Compréhension des harmonies colorées et impact émotionnel'],
        ];

        $technique = fake()->unique()->randomElement($techniques);

        return [
            'name' => $technique['name'],
            'description' => $technique['description'],
            'proficiency_level' => fake()->randomElement(['beginner', 'intermediate', 'advanced', 'expert']),
            'display_order' => fake()->numberBetween(0, 100),
        ];
    }
}

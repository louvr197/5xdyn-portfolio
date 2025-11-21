<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $webProjects = [
            'Plateforme E-commerce', 'Application de Gestion de Tâches', 'Tableau de Bord Réseaux Sociaux',
            'CMS de Blog', 'Site Portfolio', 'Système de Réservation Restaurant',
            'Tracker de Fitness', 'Plateforme Immobilière', 'Système de Gestion d\'Apprentissage'
        ];

        $artProjects = [
            'Série de Portraits', 'Collection de Paysages', 'Série de Sculptures Abstraites',
            'Études de Nature Morte', 'Collection de Dessins de Figure', 'Série de Poteries en Céramique',
            'Installation Médias Mixtes', 'Exposition de Peintures à l\'Huile', 'Série Aquarelle Nature',
            'Commission Sculpture Bronze', 'Études de Portraits au Fusain', 'Collection de Figurines en Argile'
        ];

        $isWebProject = fake()->boolean(50);
        $title = fake()->randomElement($isWebProject ? $webProjects : $artProjects);
        $slug = str($title)->slug()->toString();

        return [
            'user_id' => \App\Models\User::factory(),
            'title' => $title,
            'slug' => $slug . '-' . fake()->unique()->numberBetween(1, 9999),
            'short_description' => fake()->sentence(12),
            'long_description' => fake()->paragraphs(3, true),
            'type' => $isWebProject
                ? fake()->randomElement(['website', 'mobile_app', 'api'])
                : fake()->randomElement(['design', 'other']),
            'status' => fake()->randomElement(['draft', 'published', 'archived']),
            'context' => fake()->randomElement(['academic', 'internship', 'freelance', 'personal', 'professional']),
            'completion_date' => fake()->dateTimeBetween('-2 years', 'now'),
            'client' => fake()->boolean(40) ? fake()->company() : null,
            'duration_hours' => fake()->numberBetween(10, 500),
            'role' => $isWebProject
                ? fake()->randomElement(['Développeur Full Stack', 'Développeur Frontend', 'Développeur Backend', 'Lead Développeur'])
                : fake()->randomElement(['Artiste', 'Sculpteur', 'Peintre', 'Artiste Commissionnée']),
            'main_image' => 'projects/' . fake()->uuid() . '.jpg',
            'github_link' => $isWebProject && fake()->boolean(70) ? 'https://github.com/user/' . $slug : null,
            'site_link' => fake()->boolean(40) ? 'https://' . $slug . '.example.com' : null,
            'display_order' => fake()->numberBetween(0, 100),
        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Technology>
 */
class TechnologyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $technologies = [
            // Backend
            ['name' => 'PHP', 'category' => 'backend', 'logo_path' => 'logos/php.svg', 'color_code' => '#777BB4'],
            ['name' => 'Laravel', 'category' => 'backend', 'logo_path' => 'logos/laravel.svg', 'color_code' => '#FF2D20'],
            ['name' => 'Node.js', 'category' => 'backend', 'logo_path' => 'logos/nodejs.svg', 'color_code' => '#339933'],
            ['name' => 'Python', 'category' => 'backend', 'logo_path' => 'logos/python.svg', 'color_code' => '#3776AB'],
            ['name' => 'MySQL', 'category' => 'database', 'logo_path' => 'logos/mysql.svg', 'color_code' => '#4479A1'],

            // Frontend
            ['name' => 'React', 'category' => 'frontend', 'logo_path' => 'logos/react.svg', 'color_code' => '#61DAFB'],
            ['name' => 'Vue.js', 'category' => 'frontend', 'logo_path' => 'logos/vue.svg', 'color_code' => '#4FC08D'],
            ['name' => 'TypeScript', 'category' => 'frontend', 'logo_path' => 'logos/typescript.svg', 'color_code' => '#3178C6'],
            ['name' => 'Tailwind CSS', 'category' => 'frontend', 'logo_path' => 'logos/tailwind.svg', 'color_code' => '#06B6D4'],
            ['name' => 'Inertia.js', 'category' => 'frontend', 'logo_path' => 'logos/inertia.svg', 'color_code' => '#9553E9'],

            // Traditional Art Materials (as "technologies")
            ['name' => 'Peinture à l\'Huile', 'category' => 'other', 'logo_path' => 'logos/oil-paint.svg', 'color_code' => '#8B4513'],
            ['name' => 'Aquarelle', 'category' => 'other', 'logo_path' => 'logos/watercolor.svg', 'color_code' => '#4682B4'],
            ['name' => 'Acrylique', 'category' => 'other', 'logo_path' => 'logos/acrylic.svg', 'color_code' => '#FF6347'],
            ['name' => 'Fusain', 'category' => 'other', 'logo_path' => 'logos/charcoal.svg', 'color_code' => '#36454F'],
            ['name' => 'Argile', 'category' => 'other', 'logo_path' => 'logos/clay.svg', 'color_code' => '#B87333'],
            ['name' => 'Crayons Graphite', 'category' => 'other', 'logo_path' => 'logos/pencil.svg', 'color_code' => '#2F4F4F'],

            // DevOps
            ['name' => 'Git', 'category' => 'devops', 'logo_path' => 'logos/git.svg', 'color_code' => '#F05032'],
            ['name' => 'Docker', 'category' => 'devops', 'logo_path' => 'logos/docker.svg', 'color_code' => '#2496ED'],
            ['name' => 'GitHub Actions', 'category' => 'devops', 'logo_path' => 'logos/github-actions.svg', 'color_code' => '#2088FF'],
        ];

        $tech = fake()->unique()->randomElement($technologies);

        return [
            'user_id' => 1, // Default to first user
            'name' => $tech['name'],
            'category' => $tech['category'],
            'proficiency_level' => fake()->randomElement(['beginner', 'intermediate', 'advanced', 'expert']),
            'logo_path' => $tech['logo_path'],
            'color_code' => $tech['color_code'],
            'display_order' => fake()->numberBetween(0, 100),
        ];
    }
}

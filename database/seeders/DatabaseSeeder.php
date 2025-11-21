<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create users first
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'name' => 'Sophie Dupont',
            'email' => 'sophie@example.com',
            'password' => bcrypt('password'),
        ]);

        $this->command->info('✅ Created 2 users (Test User & Sophie Dupont)');

        // Seed everything else in order
        $this->call([
            TechnologySeeder::class,
            TechniqueSeeder::class,
            InformationSeeder::class,
            ExperienceSeeder::class,
            FormationSeeder::class,
            ProjectSeeder::class,
            GallerySeeder::class,
            ImageSeeder::class,
        ]);
    }
}

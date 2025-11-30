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
        // Create admin user first
        User::factory()->withoutTwoFactor()->create([
            'name' => 'Admin',
            'slug' => 'admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Create regular users
        User::factory()->withoutTwoFactor()->create([
            'name' => 'Test User',
            'slug' => 'test-user',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        User::factory()->withoutTwoFactor()->create([
            'name' => 'Test User 2',
            'slug' => 'test-user-2',
            'email' => 'test2@example.com',
            'password' => bcrypt('password'),
        ]);

        $this->command->info('✅ Created 3 users (Admin, Test User & Test User 2)');

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

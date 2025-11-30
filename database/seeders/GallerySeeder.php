<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Project;
use App\Models\Gallery;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $webDev = User::where('email', 'test@example.com')->first();
        $testUser2 = User::where('email', 'test2@example.com')->first();

        // Web developer galleries (1 per project + 1 subgallery)
        if ($webDev) {
            $webProjects = Project::where('user_id', $webDev->id)->get();
            foreach ($webProjects as $project) {
                $mainGallery = Gallery::factory()->create([
                    'project_id' => $project->id,
                    'title' => 'Captures d\'écran',
                ]);

                // Create a subgallery for the first project
                if ($project->id === $webProjects->first()->id) {
                    Gallery::factory()->create([
                        'project_id' => $project->id,
                        'parent_id' => $mainGallery->id,
                        'title' => 'Détails UI',
                    ]);
                }
            }
        }

        // Test User 2 galleries (2 per project)
        if ($testUser2) {
            $user2Projects = Project::where('user_id', $testUser2->id)->get();
            $galleryTypes = ['Galerie Principale', 'Détails'];

            foreach ($user2Projects as $project) {
                foreach ($galleryTypes as $galleryTitle) {
                    Gallery::factory()->create([
                        'project_id' => $project->id,
                        'title' => $galleryTitle,
                    ]);
                }
            }
        }

        $this->command->info('✅ Created ' . Gallery::count() . ' galleries');
    }
}

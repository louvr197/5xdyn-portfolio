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
        $artist = User::where('email', 'sophie@example.com')->first();

        // Web developer galleries (1 per project)
        if ($webDev) {
            $webProjects = Project::where('user_id', $webDev->id)->get();
            foreach ($webProjects as $project) {
                Gallery::factory()->create([
                    'project_id' => $project->id,
                    'title' => 'Captures d\'écran',
                ]);
            }
        }

        // Artist galleries (3 per project)
        if ($artist) {
            $artProjects = Project::where('user_id', $artist->id)->get();
            $galleryTypes = ['Galerie Principale', 'Processus de Création', 'Esquisses'];

            foreach ($artProjects as $project) {
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

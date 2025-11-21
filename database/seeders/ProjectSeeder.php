<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Project;
use App\Models\Technology;
use App\Models\Technique;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $webDev = User::where('email', 'test@example.com')->first();
        $artist = User::where('email', 'sophie@example.com')->first();
        $technologies = Technology::all();
        $techniques = Technique::all();

        // Web developer projects
        if ($webDev) {
            $webProjects = Project::factory(8)->create(['user_id' => $webDev->id]);

            foreach ($webProjects as $project) {
                // Attach web dev technologies
                $project->technologies()->attach(
                    $technologies->whereIn('category', ['backend', 'frontend', 'database', 'devops'])
                        ->random(rand(3, 5))
                        ->pluck('id')
                );
            }
        }

        // Artist projects
        if ($artist) {
            $artProjects = Project::factory(10)->create(['user_id' => $artist->id]);

            foreach ($artProjects as $project) {
                // Attach art materials
                $project->technologies()->attach(
                    $technologies->where('category', 'other')
                        ->random(rand(2, 4))
                        ->pluck('id')
                );

                // Attach art techniques
                $project->techniques()->attach(
                    $techniques->random(rand(2, 4))->pluck('id')
                );
            }
        }

        $this->command->info('✅ Created ' . Project::count() . ' projects');
    }
}

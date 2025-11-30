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
        $testUser2 = User::where('email', 'test2@example.com')->first();
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

        // Test User 2 - Artist projects (paintings, drawings, prints, screen prints)
        if ($testUser2) {
            $artistProjects = [
                [
                    'title' => 'Série de Portraits Contemporains',
                    'slug' => 'serie-portraits-contemporains',
                    'short_description' => 'Collection de 12 portraits à l\'huile explorant l\'identité moderne',
                    'type' => 'design',
                    'status' => 'published',
                ],
                [
                    'title' => 'Paysages Urbains - Sérigraphies',
                    'slug' => 'paysages-urbains-serigraphies',
                    'short_description' => 'Série de 8 sérigraphies représentant des scènes urbaines abstraites',
                    'type' => 'design',
                    'status' => 'published',
                ],
                [
                    'title' => 'Collection Aquarelle Nature',
                    'slug' => 'collection-aquarelle-nature',
                    'short_description' => 'Études botaniques à l\'aquarelle, inspiration japonaise',
                    'type' => 'design',
                    'status' => 'published',
                ],
                [
                    'title' => 'Affiches Typographiques',
                    'slug' => 'affiches-typographiques',
                    'short_description' => 'Série d\'affiches expérimentales mêlant typographie et illustration',
                    'type' => 'design',
                    'status' => 'published',
                ],
                [
                    'title' => 'Dessins au Fusain - Corps en Mouvement',
                    'slug' => 'dessins-fusain-corps-mouvement',
                    'short_description' => 'Études de mouvement et d\'anatomie au fusain et pierre noire',
                    'type' => 'other',
                    'status' => 'published',
                ],
                [
                    'title' => 'Gravures Abstraites',
                    'slug' => 'gravures-abstraites',
                    'short_description' => 'Exploration de la gravure sur métal avec motifs géométriques',
                    'type' => 'design',
                    'status' => 'draft',
                ],
            ];

            foreach ($artistProjects as $projectData) {
                $project = Project::factory()->create([
                    'user_id' => $testUser2->id,
                    'title' => $projectData['title'],
                    'slug' => $projectData['slug'],
                    'short_description' => $projectData['short_description'],
                    'type' => $projectData['type'],
                    'status' => $projectData['status'],
                    'context' => 'personal',
                    'role' => 'Artiste',
                ]);

                // Attach art materials/technologies
                if ($technologies->where('category', 'other')->count() > 0) {
                    $project->technologies()->attach(
                        $technologies->where('category', 'other')
                            ->random(min(3, $technologies->where('category', 'other')->count()))
                            ->pluck('id')
                    );
                }

                // Attach art techniques
                if ($techniques->count() > 0) {
                    $project->techniques()->attach(
                        $techniques->random(min(2, $techniques->count()))->pluck('id')
                    );
                }
            }
        }

        $this->command->info('✅ Created ' . Project::count() . ' projects');
    }
}

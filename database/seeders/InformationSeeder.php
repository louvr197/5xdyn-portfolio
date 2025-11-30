<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Information;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class InformationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $webDev = User::where('email', 'test@example.com')->first();
        $testUser2 = User::where('email', 'test2@example.com')->first();

        if ($webDev) {
            // Télécharge une photo de profil pour le développeur
            $picturePath = $this->downloadProfilePicture(1);

            Information::factory()->create([
                'user_id' => $webDev->id,
                'first_name' => 'Test',
                'last_name' => 'User',
                'professional_title' => 'Développeur Full Stack',
                'email' => 'test@example.com',
                'phone' => '+33 6 12 34 56 78',
                'location' => 'Paris, France',
                'availability' => 'Disponible pour de nouveaux projets',
                'bio' => 'Développeur passionné avec plus de 5 ans d\'expérience dans la création d\'applications web modernes. Spécialisé dans Laravel, Vue.js et les architectures scalables. J\'aime résoudre des problèmes complexes et créer des expériences utilisateur exceptionnelles.',
                'picture_path' => $picturePath,
                'theme_color' => '#0ea5e9', // Sky blue
                'social_links' => [
                    'github' => 'https://github.com/testuser',
                    'linkedin' => 'https://linkedin.com/in/testuser',
                    'twitter' => 'https://twitter.com/testuser',
                ],
            ]);
        }

        if ($testUser2) {
            // Télécharge une photo de profil pour le designer
            $picturePath = $this->downloadProfilePicture(2);

            Information::factory()->create([
                'user_id' => $testUser2->id,
                'first_name' => 'Marie',
                'last_name' => 'Dupont',
                'professional_title' => 'Designer UI/UX',
                'email' => 'test2@example.com',
                'phone' => '+33 6 98 76 54 32',
                'location' => 'Lyon, France',
                'availability' => 'Ouvert aux collaborations artistiques',
                'bio' => 'Artiste et designer avec une approche créative unique. Je crée des expériences visuelles qui allient tradition et modernité. Mon travail explore les frontières entre l\'art numérique et les techniques traditionnelles de peinture et de sérigraphie.',
                'picture_path' => $picturePath,
                'theme_color' => '#ec4899', // Pink
                'social_links' => [
                    'behance' => 'https://behance.net/mariedupont',
                    'linkedin' => 'https://linkedin.com/in/marie-dupont',
                    'instagram' => 'https://instagram.com/marie.design',
                ],
            ]);
        }

        $this->command->info('✅ Created ' . Information::count() . ' information records');
    }

    /**
     * Download a profile picture from Picsum Photos
     */
    private function downloadProfilePicture(int $seed): ?string
    {
        try {
            // Utilise un seed différent pour chaque utilisateur
            $imageUrl = "https://i.pravatar.cc/400?img={$seed}";
            $imageContent = file_get_contents($imageUrl);

            if ($imageContent === false) {
                return null;
            }

            // Génère un nom de fichier unique
            $filename = 'profile-' . $seed . '-' . Str::random(10) . '.jpg';
            $path = 'profiles/' . $filename;

            // Sauvegarde l'image
            Storage::disk('public')->put($path, $imageContent);

            return $path;
        } catch (\Exception $e) {
            $this->command->warn("⚠️  Could not download profile picture: {$e->getMessage()}");
            return null;
        }
    }
}

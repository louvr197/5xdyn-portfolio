<?php

namespace Database\Seeders;

use App\Models\Gallery;
use App\Models\Image;
use Illuminate\Database\Seeder;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $galleries = Gallery::all();

        foreach ($galleries as $gallery) {
            $imageCount = $gallery->project->user->email === 'test@example.com'
                ? rand(3, 6)  // Web dev projects: 3-6 images
                : rand(4, 8); // Art projects: 4-8 images

            Image::factory($imageCount)->create([
                'gallery_id' => $gallery->id,
            ]);
        }

        $this->command->info('✅ Created ' . Image::count() . ' images');
    }
}

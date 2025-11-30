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
            $imageCount = in_array($gallery->project->user->email, ['test@example.com', 'test2@example.com'])
                ? rand(3, 6)  // 3-6 images per gallery
                : rand(4, 8);

            Image::factory($imageCount)->create([
                'gallery_id' => $gallery->id,
            ]);
        }

        $this->command->info('✅ Created ' . Image::count() . ' images');
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Image>
 */
class ImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'gallery_id' => \App\Models\Gallery::factory(),
            'image_path' => 'projects/' . fake()->uuid() . '.jpg',
            'alt_text' => fake()->sentence(5),
            'description' => fake()->optional(0.6)->sentence(),
            'display_order' => fake()->numberBetween(0, 20),
        ];
    }
}

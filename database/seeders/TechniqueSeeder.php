<?php

namespace Database\Seeders;

use App\Models\Technique;
use Illuminate\Database\Seeder;

class TechniqueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Technique::factory(12)->create();

        $this->command->info('✅ Created ' . Technique::count() . ' techniques');
    }
}

<?php

namespace Database\Seeders;

use App\Models\Technique;
use App\Models\User;
use Illuminate\Database\Seeder;

class TechniqueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            Technique::factory(4)->create([
                'user_id' => $user->id,
            ]);
        }

        $this->command->info('✅ Created ' . Technique::count() . ' techniques for ' . $users->count() . ' users');
    }
}

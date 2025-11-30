<?php

namespace Database\Seeders;

use App\Models\Technology;
use App\Models\User;
use Illuminate\Database\Seeder;

class TechnologySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            Technology::factory(6)->create([
                'user_id' => $user->id,
            ]);
        }

        $this->command->info('✅ Created ' . Technology::count() . ' technologies for ' . $users->count() . ' users');
    }
}

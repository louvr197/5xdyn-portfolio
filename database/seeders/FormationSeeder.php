<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Formation;
use Illuminate\Database\Seeder;

class FormationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            Formation::factory(2)->create(['user_id' => $user->id]);
        }

        $this->command->info('✅ Created ' . Formation::count() . ' formations');
    }
}

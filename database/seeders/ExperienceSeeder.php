<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Experience;
use Illuminate\Database\Seeder;

class ExperienceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        
        foreach ($users as $user) {
            Experience::factory(3)->create(['user_id' => $user->id]);
        }
        
        $this->command->info('✅ Created ' . Experience::count() . ' experiences');
    }
}

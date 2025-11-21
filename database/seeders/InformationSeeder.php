<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Information;
use Illuminate\Database\Seeder;

class InformationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $webDev = User::where('email', 'test@example.com')->first();
        $artist = User::where('email', 'sophie@example.com')->first();

        if ($webDev) {
            Information::factory()->create([
                'user_id' => $webDev->id,
                'first_name' => 'Test',
                'last_name' => 'User',
                'professional_title' => 'Développeur Full Stack',
                'email' => 'test@example.com',
            ]);
        }        if ($artist) {
            Information::factory()->create([
                'user_id' => $artist->id,
                'first_name' => 'Sophie',
                'last_name' => 'Dupont',
                'professional_title' => 'Artiste Peintre & Sculpteur',
                'email' => 'sophie@example.com',
            ]);
        }

        $this->command->info('✅ Created ' . Information::count() . ' information records');
    }
}

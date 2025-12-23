<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tournament;
use App\Models\Participant;
use App\Models\User;
use Carbon\Carbon;

class LargeTournamentSeeder extends Seeder
{
    public function run(): void
    {
        // Get first user or create admin
        $user = User::first() ?? User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
        ]);

        // Create Tournament
        $tournament = Tournament::create([
            'name' => 'Grand Championship (32 Fighters)',
            'description' => 'A large scale tournament for testing bracket generation with 32 participants.',
            'start_date' => Carbon::now()->addWeeks(2),
            'end_date' => Carbon::now()->addWeeks(2)->addDays(1),
            'location' => 'Grand Arena',
            'status' => 'draft',
            'user_id' => $user->id,
        ]);

        // Create 32 Participants
        for ($i = 1; $i <= 32; $i++) {
            Participant::create([
                'tournament_id' => $tournament->id,
                'name' => 'Fighter ' . $i,
                'dojo' => 'Dojo ' . ceil($i / 4),
                'seed' => 0,
            ]);
        }

        $this->command->info('Created tournament "' . $tournament->name . '" with 32 participants.');
    }
}

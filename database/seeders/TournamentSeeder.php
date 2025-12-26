<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tournament;
use App\Models\Participant;
use App\Models\User;
use Carbon\Carbon;

class TournamentSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure Admin Exists
        $user = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'),
            ]
        );

        // 1. Tournament with 6 Participants (Testing 8-slot bracket, 2 Byes)
        $tournament6 = Tournament::create([
            'name' => 'Demo: 6 Participants (8-Slot)',
            'description' => 'Test case for Power-of-2 logic. Should generate 8 slots with 2 Byes.',
            'start_date' => Carbon::now()->addDay(),
            'end_date' => Carbon::now()->addDays(2),
            'location' => 'Test Arena 1',
            'status' => 'active',
            'user_id' => $user->id,
        ]);

        $participants6 = [
            'Player 1 (Seed 1)',
            'Player 2 (Seed 2)',
            'Player 3 (Seed 3)',
            'Player 4 (Seed 4)',
            'Player 5 (Seed 5)',
            'Player 6 (Seed 6)'
        ];

        foreach ($participants6 as $index => $name) {
            Participant::create([
                'tournament_id' => $tournament6->id,
                'name' => $name,
                'seed' => $index + 1 // 1-based seed
            ]);
        }

        // Generate Bracket for 6
        $bracketService = new \App\Services\BracketService();
        $bracketService->generateBracket($tournament6);


        // 2. Tournament with 13 Participants (Testing 16-slot bracket, 3 Byes)
        $tournament13 = Tournament::create([
            'name' => 'Demo: 13 Participants (16-Slot)',
            'description' => 'Test case for Power-of-2 logic. Should generate 16 slots with 3 Byes.',
            'start_date' => Carbon::now()->addWeek(),
            'end_date' => Carbon::now()->addWeeks(2),
            'location' => 'Test Arena 2',
            'status' => 'active',
            'user_id' => $user->id,
        ]);

        $participants13 = [];
        for ($i = 1; $i <= 13; $i++) {
            $participants13[] = "Contender $i (Seed $i)";
        }

        foreach ($participants13 as $index => $name) {
            Participant::create([
                'tournament_id' => $tournament13->id,
                'name' => $name,
                'seed' => $index + 1
            ]);
        }

        // Generate Bracket for 13
        $bracketService->generateBracket($tournament13);

    }
}

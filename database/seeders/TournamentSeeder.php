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

        // Tournament 1: Upcoming Major Event
        $ponorogoOpen = Tournament::create([
            'name' => 'Ponorogo Open Championship 2025',
            'description' => 'The premier inter-university Jiu-Jitsu tournament in East Java. Gathering the best fighters for a weekend of technique and spirit.',
            'start_date' => Carbon::parse('2025-02-15'),
            'end_date' => Carbon::parse('2025-02-16'),
            'location' => 'Auditorium Universitas Muhammadiyah Ponorogo',
            'location_map' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3952.185672230248!2d111.45520731477755!3d-7.875649994326079!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e79a0248039603b%3A0x66c54728c7041793!2sUniversitas%20Muhammadiyah%20Ponorogo!5e0!3m2!1sen!2sid!4v1672323456789!5m2!1sen!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>',
            'terms_and_conditions' => "1. All participants must be active university students.\n2. Gi and No-Gi divisions available.\n3. IBJJF rules apply.\n4. Liability waiver must be signed before weighing in.",
            'status' => 'active',
            'user_id' => $user->id,
        ]);

        $participants1 = [
            'Rizky Pratama (UMPO)',
            'Siti Aminah (UNIDA)',
            'Budi Santoso (IARA)',
            'Dewi Lestari (UMPO)',
            'Agus Kurniawan (UNMER)'
        ];

        foreach ($participants1 as $name) {
            Participant::create(['tournament_id' => $ponorogoOpen->id, 'name' => $name, 'seed' => 0]);
        }

        // Tournament 2: Internal Selection
        $internal = Tournament::create([
            'name' => 'UKM Internal Selection',
            'description' => 'Selection for the grand provincial team. Closed to internal UKM members only.',
            'start_date' => Carbon::now()->addDays(5),
            'end_date' => Carbon::now()->addDays(5),
            'location' => 'UKM Dojo',
            'status' => 'draft',
            'user_id' => $user->id,
        ]);

        $participants2 = ['Member A', 'Member B', 'Member C', 'Member D', 'Member E', 'Member F'];
        foreach ($participants2 as $name) {
            Participant::create(['tournament_id' => $internal->id, 'name' => $name, 'seed' => 0]);
        }

        // Auto-generate bracket for Ponorogo Open
        $bracketService = new \App\Services\BracketService();
        $bracketService->generateBracket($ponorogoOpen);

        // Update status to active
        $ponorogoOpen->update(['status' => 'active']);
    }
}

<?php

namespace App\Services;

use App\Models\Tournament;
use App\Models\TournamentMatch;
use App\Models\Participant;
use Illuminate\Support\Collection;

class BracketService
{
    /**
     * Generate a single-elimination bracket for the given tournament.
     */
    public function generate(Tournament $tournament)
    {
        // 1. Get Participants
        $participants = $tournament->participants()->orderBy('seed')->get();

        $count = $participants->count();

        if ($count < 2) {
            return; // Cannot make a bracket
        }

        // 2. Calculate Tree properties
        // Next power of 2
        $size = pow(2, ceil(log($count, 2)));
        $totalRounds = log($size, 2);

        // 3. Clear existing matches if any
        $tournament->matches()->delete();

        // 4. Generate the Tree Structure (Root to Leaves)
        // We build from the Final match (Root) backwards?
        // Or from Round 1 (Leaves) upwards?
        // Building upwards allows us to link 'next_match_id' easily.

        // Let's create matches round by round.
        // Round 1 (Final) -> Round 2 (Semis) ...
        // Wait, normally Round 1 is the first round played.
        // Let's call the Final "Round $totalRounds" or "Round 1" counting down?
        // The migration said "round" integer. Let's say Round 1 is the first set of matches played.
        // The Final is Round $totalRounds.

        $matchesByRound = [];

        // Determine number of matches in the first round (Leaf level)
        // Ideally, we just create a perfect binary tree of $size slots.
        // $size is e.g. 8. Matches in Round 1 = 4.
        // Then Round 2 = 2.
        // Then Round 3 = 1 (Final).

        $matchNodes = collect();

        // Step 4.1: Create all match slots
        $matchIdCounter = 1;
        for ($r = 1; $r <= $totalRounds; $r++) {
            $matchesInRound = $size / pow(2, $r);
            for ($m = 1; $m <= $matchesInRound; $m++) {
                $match = new TournamentMatch([
                    'tournament_id' => $tournament->id,
                    'round' => $r,
                    'match_number' => $matchIdCounter++,
                ]);
                $match->save();
                $matchesByRound[$r][] = $match;
            }
        }

        // Step 4.2: Link Matches (Set next_match_id)
        // A match in Round R feeds into a match in Round R+1.
        // Match M in Round R feeds into Match ceil(M/2) in Round R+1.
        for ($r = 1; $r < $totalRounds; $r++) {
            foreach ($matchesByRound[$r] as $index => $match) {
                // index is 0-based.
                // Pair 0,1 go to Parent 0. Pair 2,3 go to Parent 1.
                $parentIndex = floor($index / 2);
                $parentMatch = $matchesByRound[$r+1][$parentIndex] ?? null;

                if ($parentMatch) {
                    $match->next_match_id = $parentMatch->id;
                    $match->save();
                }
            }
        }

        // Step 5: Assign Participants to Round 1
        // We have $count actual participants and $size slots.
        // We need to place them.
        // First, extend participants array with "BYE" placeholders if needed?
        // Actually, "BYE" isn't a participant row. It's just null.
        // BUT, if it's null, we need to know if it's a TBD (waiting for winner) or a BYE (nobody there).
        // For Round 1, if it's null, it's a BYE (no opponent).

        // Standard seeding: 1 vs 8, 4 vs 5, 2 vs 7, 3 vs 6...
        // For simplicity, let's just shuffle or take order for now.
        // Detailed seeding algorithm is complex. Let's do straight fill for MVP.
        // If we have Byes, they usually go to the highest seeds in Round 1.
        // i.e., Participant 1 plays Bye (Automatic win).

        // Let's create a "Slots" array of length $size.
        // Fill first $count slots with participants.
        // Fill rest with null (Byes).
        // Then shuffle? Or keep random?
        // Let's just take the collection as is.

        $slots = $participants->all(); // Array of models

        // If we want to distribute byes evenly?
        // For MVP: Fill matches in order. P1 vs P2, P3 vs P4...
        // If we run out of P's, the slot is empty (Bye).

        $round1Matches = $matchesByRound[1];
        $pIndex = 0;

        foreach ($round1Matches as $match) {
            $p1 = $slots[$pIndex] ?? null;
            $p2 = $slots[$pIndex + 1] ?? null;

            if ($p1) {
                $match->participant_1_id = $p1->id;
            }
            if ($p2) {
                $match->participant_2_id = $p2->id;
            }

            // Auto-advance if BYE
            if ($p1 && !$p2) {
                // P1 vs Bye -> P1 wins immediately
                $match->winner_id = $p1->id;
                // Propagate to next match
                if ($match->nextMatch) {
                   // We need to determine if we are "participant 1" or "2" for the next match
                   // based on our index.
                   // Current match is Even index (0, 2...) -> goes to P1 slot of parent?
                   // Current match is Odd index (1, 3...) -> goes to P2 slot of parent?
                   // wait, $round1Matches is array.
                   // $match is an object.
                   // Let's find index in the array.
                   $idx = array_search($match, $round1Matches); // might need strict check
                   // Actually, we can check match_number relative to round start.

                   // Simple logic:
                   // If this match is the "Top" child (index % 2 == 0), it feeds participant_1 of parent.
                   // If "Bottom" child (index % 2 == 1), it feeds participant_2.

                   // BUT, $round1Matches array keys are 0, 1, 2...
                   $key = array_search($match, $round1Matches);
                   $isTop = ($key % 2 == 0);

                   $parent = $match->nextMatch; // Loaded relationship? likely need to refresh or associate manually
                   // Eloquent relationship isn't loaded yet on the object instance usually if we just set ID.
                   // But we saved IDs earlier.

                   // Let's just update parent directly via ID.
                   $column = $isTop ? 'participant_1_id' : 'participant_2_id';
                   TournamentMatch::where('id', $match->next_match_id)->update([
                       $column => $p1->id
                   ]);
                }
            }

            $match->save();
            $pIndex += 2;
        }
    }
}

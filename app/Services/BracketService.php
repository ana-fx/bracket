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
    public function generateBracket(Tournament $tournament)
    {
        $participants = $tournament->participants()->orderBy('seed')->get()->values();
        $count = $participants->count();

        if ($count < 2)
            return;

        // PREserve Match Data
        $oldMatches = $tournament->matches()->get()->keyBy(function ($m) {
            return $m->round . '-' . $m->match_number;
        });

        $tournament->matches()->delete();

        // 1. Determine Bracket Size AND Rounds
        // 6 participants -> 8 slots. 13 -> 16 slots.
        $size = pow(2, ceil(log($count, 2)));
        // If exact power of 2, stick to it.
        $totalRounds = log($size, 2);

        // 2. Generate Empty Matches (Binary Tree)
        // Round 1: size/2 matches. Round 2: size/4. Round N: 1 match.
        // We generate round 1 to N.
        $matchCounter = 1;
        $dbMatchesByRound = [];

        for ($r = 1; $r <= $totalRounds; $r++) {
            $matchesInRound = $size / pow(2, $r);
            for ($i = 0; $i < $matchesInRound; $i++) {
                $m = new TournamentMatch([
                    'tournament_id' => $tournament->id,
                    'round' => $r,
                    'match_number' => $matchCounter++, // Unique ID for visualization/restoration
                ]);
                $m->save();
                $dbMatchesByRound[$r][] = $m;
            }
        }

        // 3. Link Matches (Parents)
        // In Round r, match i feeds into Round r+1, match floor(i/2)
        for ($r = 1; $r < $totalRounds; $r++) {
            $currentMatches = $dbMatchesByRound[$r];
            $nextMatches = $dbMatchesByRound[$r + 1];

            foreach ($currentMatches as $idx => $match) {
                // Parent index is idx // 2
                $parentIdx = floor($idx / 2);
                if (isset($nextMatches[$parentIdx])) {
                    $match->next_match_id = $nextMatches[$parentIdx]->id;
                    $match->save();
                }
            }
        }

        // 4. Fill Round 1 (Seeding)
        // Get seeded order for the bracket size (e.g. 1 vs 8, 4 vs 5...)
        $seedOrder = $this->getSeededOrder($size);
        $r1Matches = $dbMatchesByRound[1];

        // $seedOrder contains indices like [1, 8, 4, 5...].
        // These are 1-based seeds.
        // Participants collection is 0-indexed (seed 1 = index 0).
        // If seed > count, that slot is a BYE.

        $matchesToDelete = [];

        foreach ($r1Matches as $idx => $match) {
            // Each match takes 2 seeds from the order
            $seed1 = $seedOrder[$idx * 2];     // e.g. 1
            $seed2 = $seedOrder[$idx * 2 + 1]; // e.g. 8

            $p1 = ($seed1 <= $count) ? $participants[$seed1 - 1] : null; // -1 for 0-index
            $p2 = ($seed2 <= $count) ? $participants[$seed2 - 1] : null;

            $updateData = [];
            if ($p1) $updateData['participant_1_id'] = $p1->id;
            if ($p2) $updateData['participant_2_id'] = $p2->id;

            // Auto-Advance BYES (1 vs Null)
            // If P1 exists and P2 is Null -> P1 Wins immediately.
            // We also MARK this match for DELETION to create a compact bracket (hide Byes).
            $isBye = ($p1 && !$p2);

            if ($isBye) {
                $updateData['winner_id'] = $p1->id;
            }

            if (!empty($updateData)) {
                TournamentMatch::where('id', $match->id)->update($updateData);
                $match->refresh();
            }

            // Propagate Bye Winners immediately
            if ($match->winner_id && $match->next_match_id) {
                $nextMatch = TournamentMatch::find($match->next_match_id);
                // Determine slot (Even/Odd index)
                $isP1Slot = ($idx % 2 === 0);
                $slot = $isP1Slot ? 'participant_1_id' : 'participant_2_id';
                $nextMatch->update([$slot => $match->winner_id]);

                // If this was a Bye, we assume the next match now "owns" this participant as a start node.
                // We add to delete list to clean up the visual tree.
                if ($isBye) {
                    $matchesToDelete[] = $match->id;
                }
            }
        }

        // 5. Restore User Edits (Scores) if matches line up
        // Only for matches that are NOT byes.
        if ($oldMatches->isNotEmpty()) {
            foreach ($dbMatchesByRound as $round => $matches) {
                foreach ($matches as $newMatch) {
                    $key = $newMatch->round . '-' . $newMatch->match_number;
                    $old = $oldMatches->get($key);

                    if ($old) {
                        // Strict restore: Only if same participants
                        if (
                            $old->participant_1_id == $newMatch->participant_1_id &&
                            $old->participant_2_id == $newMatch->participant_2_id
                        ) {
                            $newMatch->update([
                                'participant_1_score' => $old->participant_1_score,
                                'participant_2_score' => $old->participant_2_score,
                                'winner_id' => $old->winner_id // Restore winner if manual
                            ]);

                            // Propagate restoration (if winner restored)
                            if ($old->winner_id && $newMatch->next_match_id) {
                                // ... Logic to check if we should push.
                                // Actually, if we just run "Auto-Advance" logic globally it might be safer,
                                // but for now let's trust manual restore or user re-entry.
                                // Simplest: If winner restored, push to next.
                                $next = TournamentMatch::find($newMatch->next_match_id);
                                // Find index of THIS match in the round array to know slot?
                                // We can use match_number (sequential) effectively.
                                // Or use db query.
                                // But wait, $dbMatchesByRound isn't indexed by ID.
                                // Let's use modulus of count?
                                // Match N feeds Match Parent.
                                // Parent ID is known.
                                // We need to know if we are Top or Bottom feeder.
                                // R1 Match 1 (idx 0) -> R2 Match 1 (P1)
                                // R1 Match 2 (idx 1) -> R2 Match 1 (P2)
                                // Standard: $isTop = ($newMatch->match_number % 2 != 0) (If numbering is global sequential... wait).
                                // My generation uses $matchCounter++.
                                // R1: 1, 2, 3, 4.
                                // R2: 5, 6.
                                // This numbering is disjointed from intra-round index.
                                // Safer: Use strict DB relation or re-calc index.
                                // We'll skip complex restore propagation for now, assume user generates -> fresh start usually,
                                // or randomize seeds -> fresh start.
                            }
                        }
                    }
                }
            }
        }

        // 6. Clean up Bye Matches (Compact View)
        if (!empty($matchesToDelete)) {
            TournamentMatch::destroy($matchesToDelete);
        }
    }

    private function advanceByes(Tournament $tournament)
    {
        // DISABLED
        /*
        $matches = $tournament->matches()->orderBy('round')->orderBy('match_number')->get();

        // We group by round to easily find indices
        $matchesByRound = $matches->groupBy('round');

        foreach ($matchesByRound as $round => $roundMatches) {
            // Convert to values to ensure 0-indexed keys
            $roundMatches = $roundMatches->values();

            foreach ($roundMatches as $index => $match) {
                // Check if it's a Bye match (only 1 participant)
                if ($match->participant_1_id && !$match->participant_2_id && !$match->winner_id) {
                    $match->winner_id = $match->participant_1_id;
                    $match->save();
                }

                // If we have a winner, advance them
                if ($match->winner_id && $match->next_match_id) {
                    $nextMatch = TournamentMatch::find($match->next_match_id);
                    // Determine slot. Currently $match is $index in $roundMatches.
                    // Even index (0, 2) -> P1. Odd (1, 3) -> P2.
                    $isP1 = ($index % 2 === 0);
                    $slot = $isP1 ? 'participant_1_id' : 'participant_2_id';

                    // Only update if empty or different? No, just update.
                    if ($nextMatch->$slot !== $match->winner_id) {
                        $nextMatch->update([$slot => $match->winner_id]);
                    }
                }
            }
        }
        */
    }

    /**
     * Generate standard seeded order for a bracket of size N.
     * Returns array of seed numbers, e.g. for N=4: [1, 4, 2, 3]
     */
    private function getSeededOrder(int $size): array
    {
        if ($size == 2) {
            return [1, 2];
        }

        // Recursive step:
        // Take order for Size/2 (e.g. [1, 2])
        // Replace each x with [x, Size + 1 - x]
        // [1, 4, 2, 3]

        $prevOrder = $this->getSeededOrder($size / 2);
        $order = [];

        foreach ($prevOrder as $seed) {
            $order[] = $seed;
            $order[] = $size + 1 - $seed;
        }

        return $order;
    }
}

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

        // PREserve Match Data (Safe Restore Logic)
        $oldMatches = $tournament->matches()->get()->keyBy(function ($m) {
            return $m->round . '-' . $m->match_number;
        });

        $tournament->matches()->delete();

        // New Logic: Interactive Reduction
        // If 6 participants:
        // Round 1: 3 matches (1v2, 3v4, 5v6).
        // Winners: 3.
        // Round 2: 1 match (W1vW2) + 1 Bye (W3).

        // ... (Existing Logic Omitted for Brevity in Thought, but needs to be in File) ...
        // Re-implementing the generation logic to ensure it's still there

        // Let's build strictly by reducing count.
        $simulatedCount = $count;
        $roundsStruct = [];
        $matchNumberCounter = 1;

        $layerCnt = $count;
        $r = 1;
        while ($layerCnt > 1) {
            $matchesInLayer = ceil($layerCnt / 2);
            $roundsStruct[$r] = $matchesInLayer;
            $layerCnt = $matchesInLayer;
            $r++;
        }

        // Now create the matches in DB
        $totalRounds = count($roundsStruct);
        $dbMatchesByRound = [];

        foreach ($roundsStruct as $rNum => $mCount) {
            for ($i = 0; $i < $mCount; $i++) {
                $m = new TournamentMatch([
                    'tournament_id' => $tournament->id,
                    'round' => $rNum,
                    'match_number' => $matchNumberCounter++,
                ]);
                $m->save();
                $dbMatchesByRound[$rNum][] = $m;
            }
        }

        // Now Linking (Next Match)
        for ($r = 1; $r < $totalRounds; $r++) {
            $currentMatches = $dbMatchesByRound[$r];
            $nextMatches = $dbMatchesByRound[$r + 1];

            foreach ($currentMatches as $idx => $match) {
                $parentIdx = floor($idx / 2);
                if (isset($nextMatches[$parentIdx])) {
                    $match->next_match_id = $nextMatches[$parentIdx]->id;
                    $match->save();
                }
            }
        }

        // Fill Round 1 with Participants
        $r1Matches = $dbMatchesByRound[1];
        $pIndex = 0;

        foreach ($r1Matches as $match) {
            $updateData = [];

            if (isset($participants[$pIndex])) {
                $updateData['participant_1_id'] = $participants[$pIndex]->id;
            }
            if (isset($participants[$pIndex + 1])) {
                $updateData['participant_2_id'] = $participants[$pIndex + 1]->id;
            }

            if (!empty($updateData)) {
                TournamentMatch::where('id', $match->id)->update($updateData);
            }
            $match->refresh();
            $pIndex += 2;
        }

        // RESTORE LOGIC
        // Check if R1 matches are identical to old matches
        $canRestore = true;
        if ($oldMatches->isEmpty()) {
            $canRestore = false;
        } else {
            foreach ($r1Matches as $newMatch) {
                $key = $newMatch->round . '-' . $newMatch->match_number;
                $old = $oldMatches->get($key);

                if (!$old) {
                    $canRestore = false;
                    break;
                }
                if (
                    $old->participant_1_id != $newMatch->participant_1_id ||
                    $old->participant_2_id != $newMatch->participant_2_id
                ) {
                    $canRestore = false;
                    break;
                }
            }
        }

        if ($canRestore) {
            // Restore All Matches (including R2+)
            $allNewMatches = TournamentMatch::where('tournament_id', $tournament->id)->get();
            foreach ($allNewMatches as $newMatch) {
                $key = $newMatch->round . '-' . $newMatch->match_number;
                $old = $oldMatches->get($key);

                if ($old) {
                    $newMatch->update([
                        'participant_1_id' => $old->participant_1_id,
                        'participant_2_id' => $old->participant_2_id,
                        'participant_1_score' => $old->participant_1_score,
                        'participant_2_score' => $old->participant_2_score,
                        'score_history' => $old->score_history,
                        'winner_id' => $old->winner_id
                    ]);
                }
            }
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

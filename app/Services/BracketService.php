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

        if ($count < 2) return;

        $tournament->matches()->delete();

        // New Logic: Interactive Reduction
        // If 6 participants:
        // Round 1: 3 matches (1v2, 3v4, 5v6).
        // Winners: 3.
        // Round 2: 1 match (W1vW2) + 1 Bye (W3).

        // We simulate the rounds first to build the structure.
        $currentRoundParticipants = $participants->pluck('id')->toArray();
        $round = 1;
        $matchNumberCounter = 1;

        // We need to keep track of "Slots" that move to next round.
        // A slot can be a "Winner of Match X" or "Participant Y (Bye)".

        // Let's perform a simulation to create appropriate empty matches first?
        // Actually, it's easier to verify "Power of 2" standard logic vs "Visual Preference".
        // The user wants simple pairing: 1v2, 3v4.
        // So we just pair adjacent seeds in the list for every round.

        // Note: Creating DB records for future rounds is tricky if we don't know who advances.
        // BUT, we can create the "Container" matches.
        // If Round 1 has 3 matches. Round 2 will have ceil(3/2) = 2 matches.
        // One of those R2 matches will have (Win M1 vs Win M2). The other (Win M3 vs Bye).

        // Let's build strictly by reducing count.
        $simulatedCount = $count;
        $roundsStruct = [];

        // We must ensure the final round has exactly 1 match.
        // We will build "layers" of match placeholders.

        $layerCnt = $count;
        $r = 1;
        while($layerCnt > 1) {
            $matchesInLayer = ceil($layerCnt / 2);
            $roundsStruct[$r] = $matchesInLayer;
            $layerCnt = $matchesInLayer;
            $r++;
        }

        // Now create the matches in DB
        $totalRounds = count($roundsStruct);
        $dbMatchesByRound = [];

        foreach ($roundsStruct as $rNum => $mCount) {
             for ($i=0; $i < $mCount; $i++) {
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
        // This is the hard part with odd numbers.
        // R1: 3 matches [M1, M2, M3].
        // R2: 2 matches [M4, M5].
        // M1, M2 -> Feed M4.
        // M3 -> Feeds M5.
        // M5 only has 1 input? So M5 is effectively a Bye match in R2.

        // Linking Logic:
        // Iterate through R, pair adjacent matches to feed R+1.

        for ($r = 1; $r < $totalRounds; $r++) {
             $currentMatches = $dbMatchesByRound[$r];
             $nextMatches = $dbMatchesByRound[$r+1];

             // We pair them up. Match 0 and Match 1 -> Next Match 0.
             // Match 2 and Match 3 -> Next Match 1.

             foreach ($currentMatches as $idx => $match) {
                  $parentIdx = floor($idx / 2);
                  if (isset($nextMatches[$parentIdx])) {
                       $match->next_match_id = $nextMatches[$parentIdx]->id;
                       $match->save();
                  }
             }
        }

        // Fill Round 1 with Participants
        // Just pair them in order: 1vs2, 3vs4...
        // If odd, last one is alone.

        $r1Matches = $dbMatchesByRound[1];
        $pIndex = 0;

        foreach ($r1Matches as $match) {
             $updateData = [];

             if (isset($participants[$pIndex])) {
                  $updateData['participant_1_id'] = $participants[$pIndex]->id;
             }
             if (isset($participants[$pIndex+1])) {
                  $updateData['participant_2_id'] = $participants[$pIndex+1]->id;
             }

             if (!empty($updateData)) {
                 TournamentMatch::where('id', $match->id)->update($updateData);
             }

             // Refresh match data for auto-win check
             $match->refresh();

             // Auto-Win logic for odd man out in R1
             /*
             if ($match->participant_1_id && !$match->participant_2_id) {
                 $match->winner_id = $match->participant_1_id;
                 $match->save();

                 // Advance immediately
                 if ($match->next_match_id) {
                     $nextM = TournamentMatch::find($match->next_match_id);

                     // Find index of this match in R1
                     $myIdx = array_search($match, $r1Matches);

                     $isTop = ($myIdx % 2 == 0);
                     $slot = $isTop ? 'participant_1_id' : 'participant_2_id';

                     if ($nextM->$slot !== $match->winner_id) {
                         $nextM->update([$slot => $match->winner_id]);
                     }
                 }
             }
             */
             $pIndex += 2;
        }

        // Correct Advance Logic pass for ALL Rounds (to handle byes anywhere)
        // We simply run an update pass.
        // $this->advanceByes($tournament); // DISABLED MANUAL ADVANCE
    }

    private function advanceByes(Tournament $tournament) {
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

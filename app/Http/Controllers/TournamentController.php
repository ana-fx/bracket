<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use App\Models\Participant;
use App\Services\BracketService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TournamentController extends Controller
{
    public function index()
    {
        $tournaments = Tournament::where('user_id', Auth::id())->latest()->get();
        $totalParticipants = Participant::whereIn('tournament_id', $tournaments->pluck('id'))->count();
        $completedMatches = DB::table('matches')
            ->join('tournaments', 'matches.tournament_id', '=', 'tournaments.id')
            ->where('tournaments.user_id', Auth::id())
            ->whereNotNull('winner_id')
            ->count();


        return view('admin.dashboard', compact('tournaments', 'totalParticipants', 'completedMatches'));
    }

    public function create()
    {
        return view('admin.tournaments.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|max:2048', // 2MB Max
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'location' => 'nullable|string',
            'location_map' => 'nullable|string',
            'terms_and_conditions' => 'nullable|string',
        ]);

        $coverPath = null;
        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('tournaments', 'public');
        }

        $tournament = Tournament::create([
            'name' => $request->name,
            'description' => $request->description,
            'cover_image' => $coverPath,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'location' => $request->location,
            'location_map' => $request->location_map,
            'terms_and_conditions' => $request->terms_and_conditions,
            'user_id' => Auth::id() ?? 1,
            'status' => 'draft',
        ]);


        return redirect()->route('tournaments.participants', $tournament);
    }

    public function edit(Tournament $tournament)
    {
        // Simple auth check (policy would be better, but this works for now)
        if ($tournament->user_id !== Auth::id()) {
            abort(403);
        }
        return view('admin.tournaments.edit', compact('tournament'));
    }

    public function update(Request $request, Tournament $tournament)
    {
        if ($tournament->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|max:2048',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'location' => 'nullable|string',
            'location_map' => 'nullable|string',
            'terms_and_conditions' => 'nullable|string',
        ]);

        if ($request->hasFile('cover_image')) {
            // Optional: delete old image if exists
            // Storage::disk('public')->delete($tournament->cover_image);
            $path = $request->file('cover_image')->store('tournaments', 'public');
            $tournament->cover_image = $path;
        }

        $tournament->name = $request->name;
        $tournament->description = $request->description;
        $tournament->start_date = $request->start_date;
        $tournament->end_date = $request->end_date;
        $tournament->location = $request->location;
        $tournament->location_map = $request->location_map;
        $tournament->terms_and_conditions = $request->terms_and_conditions;
        $tournament->save();

        return redirect()->route('admin.dashboard')->with('success', 'Tournament updated successfully.');
    }

    public function destroy(Tournament $tournament)
    {
        if ($tournament->user_id !== Auth::id()) {
            abort(403);
        }

        $tournament->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Tournament deleted.');
    }


    public function participants(Tournament $tournament)
    {
        $participants = $tournament->participants()->orderBy('created_at')->get();
        return view('admin.tournaments.participants', compact('tournament', 'participants'));
    }

    public function storeParticipants(Request $request, Tournament $tournament)
    {
        // Check if tournament has active matches
        if ($tournament->hasActiveMatches()) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Cannot add participants once matches have started.'], 403);
            }
            return redirect()->route('tournaments.participants', $tournament)->with('error', 'Cannot add participants once matches have started.');
        }

        // Check if it's a bulk add or single add
        if ($request->filled('participants')) {
            $request->validate(['participants' => 'required|string']);
            $names = explode("\n", $request->participants);
            $names = array_map('trim', $names);
            $names = array_filter($names);

            foreach ($names as $name) {
                if (!empty($name)) {
                    Participant::create([
                        'tournament_id' => $tournament->id,
                        'name' => $name,
                        'seed' => 0,
                    ]);
                }
            }
            return redirect()->route('tournaments.participants', $tournament)->with('success', 'Participants added.');
        }

        // Single Add Logic
        $request->validate([
            'name' => 'required|string|max:255',
            'dojo' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('participants', 'public');
        }

        Participant::create([
            'tournament_id' => $tournament->id,
            'name' => $request->name,
            'dojo' => $request->dojo,
            'image_path' => $imagePath,
            'seed' => 0,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Participant added.',
                'participant' => Participant::where('tournament_id', $tournament->id)->latest()->first()
            ]);
        }

        return redirect()->route('tournaments.participants', $tournament)->with('success', 'Participant added.');
    }

    public function destroyParticipant(Tournament $tournament, Participant $participant)
    {
        if ($tournament->hasActiveMatches()) {
            return back()->with('error', 'Cannot remove participants once matches have started.');
        }

        $participant->delete();
        return back()->with('success', 'Participant removed.');
    }

    public function updateParticipant(Request $request, Tournament $tournament, Participant $participant)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'dojo' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'dojo' => $request->dojo,
        ];

        if ($request->hasFile('image')) {
            // Delete old image if needed (optional)
            $path = $request->file('image')->store('participants', 'public');
            $data['image_path'] = $path;
        }

        $participant->update($data);

        $participant->update($data);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Participant updated.',
                // Return new data so frontend can update DOM if needed without reload
                'participant' => $participant->fresh()
            ]);
        }

        return back()->with('success', 'Participant updated.');
    }

    public function randomize(Tournament $tournament, BracketService $bracketService)
    {
        if ($tournament->hasActiveMatches()) {
            return response()->json(['success' => false, 'message' => 'Cannot shuffle bracket once matches have started.'], 403);
        }

        $participants = $tournament->participants()->get()->shuffle();

        foreach ($participants as $index => $participant) {
            $participant->update(['seed' => $index + 1]);
        }

        // Regenerate the bracket to reflect new seeds
        $bracketService->generateBracket($tournament);

        return response()->json(['success' => true, 'message' => 'Randomized!']);
    }

    public function generate(Tournament $tournament, BracketService $bracketService)
    {
        if ($tournament->hasActiveMatches()) {
            return back()->withErrors(['error' => 'Cannot regenerate bracket once matches have started.']);
        }

        if ($tournament->participants()->count() < 2) {
            return back()->withErrors(['error' => 'Not enough participants.']);
        }

        $bracketService->generateBracket($tournament);

        $tournament->update(['status' => 'active']);

        return redirect()->route('admin.tournaments.show', $tournament);
    }


    public function adminShow(Tournament $tournament)
    {
        if ($tournament->user_id !== Auth::id()) {
            abort(403);
        }

        $tournament->load('matches.participant1', 'matches.participant2', 'matches.winner');
        $matchesByRound = $tournament->matches->groupBy('round');

        return view('admin.tournaments.show', compact('tournament', 'matchesByRound'));
    }

    public function show(Tournament $tournament)
    {
        $tournament->load('matches.participant1', 'matches.participant2', 'matches.winner');

        // Group matches by round for easy display
        $matchesByRound = $tournament->matches->groupBy('round');

        return view('tournaments.show', compact('tournament', 'matchesByRound'));
    }

    public function updateMatch(Request $request, \App\Models\TournamentMatch $match)
    {
        try {
            $match->load('tournament');

            if ($match->tournament->user_id !== Auth::id()) {
                abort(403);
            }



            $request->validate([
                'participant_1_score' => 'required|integer|min:0|max:99',
                'participant_2_score' => 'required|integer|min:0|max:99',
            ]);

            $match->update([
                'participant_1_score' => $request->participant_1_score,
                'participant_2_score' => $request->participant_2_score,
            ]);



            $winnerId = null;
            $history = $match->score_history ?? [];

            if ($match->participant_1_score > $match->participant_2_score) {
                // P1 Wins
                $winnerId = $match->participant_1_id;
            } elseif ($match->participant_2_score > $match->participant_1_score) {
                // P2 Wins
                $winnerId = $match->participant_2_id;
            } else {
                // DRAW - Start Rematch Loop
                // 1. Add current score to history
                $history[] = [
                    'p1' => $match->participant_1_score,
                    'p2' => $match->participant_2_score,
                    'reason' => 'Draw'
                ];

                // 2. Reset scores for next match
                $match->update([
                    'score_history' => $history,
                    'participant_1_score' => 0,
                    'participant_2_score' => 0,
                    'winner_id' => null // Ensure no winner is set
                ]);

                // Return early so we don't process advance/revoke logic
                if ($request->wantsJson()) {
                    return response()->json(['message' => 'Draw detected. Rematch started.', 'match' => $match]);
                }
                return back()->with('success', 'Draw! Rematch started.');
            }

            if ($winnerId && $winnerId !== $match->winner_id) {
                $match->update(['winner_id' => $winnerId]);

                // Advance Winner to Next Match if exists
                if ($match->next_match_id) {
                    $nextMatch = \App\Models\TournamentMatch::find($match->next_match_id);
                    if ($nextMatch) {
                        $slot = ($match->match_number % 2 != 0) ? 'participant_1_id' : 'participant_2_id';
                        $nextMatch->update([$slot => $winnerId]);
                    }
                }
            } elseif (!$winnerId && $match->winner_id) {
                // Score changed back to non-winning? Revoke winner.
                $lastWinnerId = $match->winner_id;
                $match->update(['winner_id' => null]);

                if ($match->next_match_id) {
                    $nextMatch = \App\Models\TournamentMatch::find($match->next_match_id);
                    if ($nextMatch) {
                        // Check which slot to clear
                        $slot = ($match->match_number % 2 != 0) ? 'participant_1_id' : 'participant_2_id';
                        // Only clear if it held the old winner
                        if ($nextMatch->$slot == $lastWinnerId) {
                            $nextMatch->update([$slot => null]);
                        }
                    }
                }
            }

            if ($request->wantsJson()) {
                return response()->json(['message' => 'Match updated.', 'match' => $match]);
            }

            return back()->with('success', 'Match updated.');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Update Match Error: ' . $e->getMessage());
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Server Error: ' . $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ], 500);
            }
            throw $e;
        }
    }
}

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

    public function destroyParticipant(Tournament $tournament, Participant $participant)
    {
        $participant->delete();
        return back()->with('success', 'Participant removed.');
    }

    public function randomize(Tournament $tournament, BracketService $bracketService)
    {
        $participants = $tournament->participants()->get()->shuffle();

        foreach ($participants as $index => $participant) {
            $participant->update(['seed' => $index + 1]);
        }

        // Regenerate the bracket to reflect new seeds
        $bracketService->generate($tournament);

        return response()->json(['success' => true, 'message' => 'Randomized!']);
    }

    public function generate(Tournament $tournament, BracketService $bracketService)
    {

        if ($tournament->participants()->count() < 2) {
            return back()->withErrors(['error' => 'Not enough participants.']);
        }

        $bracketService->generate($tournament);

        $tournament->update(['status' => 'active']);

        return redirect()->route('tournaments.show', $tournament);
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
}

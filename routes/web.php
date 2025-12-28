<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Models\Tournament;

Route::get('/', function () {
    $tournaments = Tournament::where('status', 'active')->latest()->get();
    return view('home', compact('tournaments'));
});

use App\Http\Controllers\TournamentController;
use App\Http\Controllers\LoginController;

Route::get('/login', function () {
    if (Auth::check()) {
        return redirect()->route('admin.dashboard');
    }
    return view('auth.login');
})->name('login');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/dashboard', [TournamentController::class, 'index'])->name('admin.dashboard');

    Route::get('/tournaments/create', [TournamentController::class, 'create'])->name('tournaments.create');
    Route::post('/tournaments', [TournamentController::class, 'store'])->name('tournaments.store');

    // Management
    Route::get('/tournaments/{tournament}/edit', [TournamentController::class, 'edit'])->name('tournaments.edit');
    Route::put('/tournaments/{tournament}', [TournamentController::class, 'update'])->name('tournaments.update');
    Route::delete('/tournaments/{tournament}', [TournamentController::class, 'destroy'])->name('tournaments.destroy');


    // Participants Step
    Route::get('/tournaments/{tournament}/participants', [TournamentController::class, 'participants'])->name('tournaments.participants');
    Route::post('/tournaments/{tournament}/participants', [TournamentController::class, 'storeParticipants'])->name('tournaments.participants.store');
    Route::put('/tournaments/{tournament}/participants/{participant}', [TournamentController::class, 'updateParticipant'])->name('participants.update');
    Route::delete('/tournaments/{tournament}/participants/{participant}', [TournamentController::class, 'destroyParticipant'])->name('participants.destroy');
    Route::post('/tournaments/{tournament}/randomize', [TournamentController::class, 'randomize'])->name('tournaments.randomize');

    // Generate Step
    Route::post('/tournaments/{tournament}/generate', [TournamentController::class, 'generate'])->name('tournaments.generate');

    // Print Route
    Route::get('/tournaments/{tournament}/print', [TournamentController::class, 'print'])->name('tournaments.print');

    // Admin View (Managed)
    Route::get('/tournaments/{tournament}', [TournamentController::class, 'adminShow'])->name('admin.tournaments.show');
    Route::get('/tournaments/{tournament}', [TournamentController::class, 'adminShow'])->name('admin.tournaments.show');

    // Match Management
    Route::put('/matches/{match}', [TournamentController::class, 'updateMatch'])->name('matches.update');

});

Route::get('/tournaments/{tournament}', [TournamentController::class, 'show'])->name('tournaments.show');

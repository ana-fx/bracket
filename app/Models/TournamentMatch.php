<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class TournamentMatch extends Model
{
    use HasUuids;

    protected $table = 'matches'; // Explicitly set table

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'tournament_id',
        'round',
        'match_number',
        'next_match_id',
        'participant_1_id',
        'participant_2_id',
        'participant_1_score',
        'participant_2_score',
        'winner_id'
    ];

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function participant1()
    {
        return $this->belongsTo(Participant::class, 'participant_1_id');
    }

    public function participant2()
    {
        return $this->belongsTo(Participant::class, 'participant_2_id');
    }

    public function winner()
    {
        return $this->belongsTo(Participant::class, 'winner_id');
    }

    public function nextMatch()
    {
        return $this->belongsTo(TournamentMatch::class, 'next_match_id');
    }
}

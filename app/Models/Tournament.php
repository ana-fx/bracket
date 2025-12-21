<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    use HasUuids;

    protected $fillable = [
        'name',
        'cover_image',
        'description',
        'start_date',
        'end_date',
        'location',
        'location_map',
        'terms_and_conditions',
        'status',
        'best_of',
        'user_id'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }

    public function matches()
    {
        return $this->hasMany(TournamentMatch::class);
    }
}


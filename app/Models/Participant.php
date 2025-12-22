<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    use HasUuids;

    protected $fillable = ['name', 'dojo', 'image_path', 'affiliation', 'seed', 'tournament_id'];

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }
}

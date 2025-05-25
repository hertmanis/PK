<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Practice extends Model
{
    use HasFactory;

    protected $fillable = ['team_id', 'coach_id', 'title', 'description', 'scheduled_at', 'type'];


    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }
}


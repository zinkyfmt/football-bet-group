<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Match extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'home_team_id', 'away_team_id', 'stages', 'order', 'stadium', 'match_at', 'home_team_rate_value', 'away_team_rate_value', 'home_team_goal_value', 'away_team_goal_value'
    ];

    /**
     * Get the phone associated with the user.
     */
    public function homeTeam()
    {
        return $this->belongsTo(Team::class, 'home_team_id', 'id');
    }

    /**
     * Get the phone associated with the user.
     */
    public function awayTeam()
    {
        return $this->belongsTo(Team::class, 'away_team_id', 'id');
    }
}

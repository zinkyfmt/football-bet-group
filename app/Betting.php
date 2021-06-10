<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Betting extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'match_id', 'win_team_id', 'is_draw', 'is_lucky_star'
    ];

    /**
     * Get the phone associated with the user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the phone associated with the user.
     */
    public function match()
    {
        return $this->belongsTo(Match::class);
    }

    /**
     * Get the phone associated with the user.
     */
    public function winTeam()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Get the phone associated with the user.
     */
    public function result()
    {
        return $this->hasOne(Result::class);
    }
}

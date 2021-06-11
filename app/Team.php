<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'group_id', 'url', 'rank', 'played', 'win', 'draw', 'lost', 'for', 'against', 'goal_difference', 'points'
    ];

    /**
     * Get the phone associated with the user.
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}

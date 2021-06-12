<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SummaryPlayers extends Model
{
    protected $primaryKey = 'user_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'win', 'draw', 'lose', 'debit', 'rank'
    ];

    /**
     * Get the phone associated with the user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

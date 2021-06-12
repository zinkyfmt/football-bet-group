<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id','match_id', 'betting_id', 'status', 'cost'
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
    public function betting()
    {
        return $this->hasOne(Betting::class);
    }

}

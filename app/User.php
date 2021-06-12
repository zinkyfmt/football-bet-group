<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'username','role','avatar', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    /**
     * Get the phone associated with the user.
     */
    public function results()
    {
        return $this->hasMany(Result::class, 'user_id', 'id');
    }

    /**
     * Get the phone associated with the user.
     */
    public function summary()
    {
        return $this->hasOne(SummaryPlayers::class, 'user_id', 'id');
    }
}

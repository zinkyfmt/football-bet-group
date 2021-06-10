<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * Get the phone associated with the user.
     */
    public function team()
    {
        return $this->hasMany(Team::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class Player extends Model implements AuthenticatableContract{

    use Authenticatable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'player';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_name', 'app_id', 'last_seen_online', 'in_game', 'password', 'game_flavor_playing'
    ];

}

<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Player extends Model implements AuthenticatableContract {
    use Authenticatable;

    use SoftDeletes;

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
        'user_name', 'app_id', 'last_seen_online', 'in_game', 'password', 'game_flavor_playing',
    ];

    /**
     * Player rows go to other players through the API; never include the password hash.
     *
     * @var list<string>
     */
    protected $hidden = ['password'];
}

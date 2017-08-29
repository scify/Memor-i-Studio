<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GameRequest extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'game_request';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['status_id', 'player_initiator_id', 'player_opponent_id', 'game_flavor_id'];


    public function initiator()
    {
        return $this->belongsTo('App\Models\User', 'player_initiator_id', 'id');
    }

    public function opponent()
    {
        return $this->belongsTo('App\Models\User', 'player_opponent_id', 'id');
    }

    public function gameFlavor() {
        return $this->hasOne('App\Models\GameFlavor', 'id', 'game_flavor_id');
    }

}

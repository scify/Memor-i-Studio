<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GameMovement extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'game_movement';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['game_request_id', 'movement_json', 'timestamp',
        'player_id'];


    public function player()
    {
        return $this->hasOne('App\Models\Player', 'id','player_id');
    }


    public function gameRequest() {
        return $this->hasOne('App\Models\GameRequest', 'id', 'game_request_id');
    }

}

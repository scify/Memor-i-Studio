<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GameFlavorReport extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'game_flavor_report';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'game_flavor_id', 'user_comment', 'user_name', 'user_email'];

    /**
     * Get the @see Resource this translation belongs to.
     */
    public function gameFlavor() {
        return $this->belongsTo('App\Models\GameFlavor', 'game_flavor_id', 'id');
    }

    /**
     * Get the @see this translation belongs to.
     */
    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GameVersion extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'game_version';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name','lang_id', 'description'];

    /**
     * Get the images for the card.
     */
    public function cards() {
        return $this->hasMany('App\Models\Card');
    }

    /**
     * Get the images for the card.
     */
    public function sounds() {
        return $this->hasMany('App\Models\Sound');
    }

    public function language() {
        return $this->hasOne('App\Models\Language', 'lang_id', 'id');
    }

    /**
     * Get the post that owns the comment.
     */
    public function creator()
    {
        return $this->belongsTo('App\User');
    }
}

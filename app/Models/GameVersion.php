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
    protected $fillable = ['name', 'version_code', 'creator_id', 'cover_img_path', 'description'];

    /**
     * Get the images for the card.
     */
    public function gameFlavors() {
        return $this->hasMany('App\Models\GameFlavor');
    }

    /**
     * Get the images for the card.
     */
    public function resourceCategories() {
        return $this->hasMany('App\Models\ResourceCategory');
    }

    public function languages() {
        return $this->belongsToMany('App\Models\GameVersionLanguage', 'game_version_language');
    }

    /**
     * Get the post that owns the comment.
     */
    public function creator()
    {
        return $this->belongsTo('App\Models\User', 'creator_id', 'id');
    }


}

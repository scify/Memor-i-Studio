<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GameVersionLanguage extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'game_version_language';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'lang_id', 'game_version_id'];

    /**
     * Get the language.
     */
    public function language() {
        return $this->belongsTo('App\Models\Language', 'lang_id', 'id');
    }

    /**
     * Get the game version.
     */
    public function gameVersion() {
        return $this->belongsTo('App\Models\GameVersion', 'game_version_id', 'id');
    }

}

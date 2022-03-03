<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class GameVersion extends Model {
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
    protected $fillable = ['name', 'version_code', 'data_pack_dir_name', 'creator_id', 'cover_img_path', 'description', 'online'];

    /**
     * Get the images for the card.
     */
    public function gameFlavors(): HasMany {
        return $this->hasMany('App\Models\GameFlavor');
    }

    /**
     * Get the images for the card.
     */
    public function resourceCategories(): HasMany {
        return $this->hasMany('App\Models\ResourceCategory');
    }

    public function gameVersionLanguages(): HasMany {
        return $this->hasMany('App\Models\GameVersionLanguage', 'game_version_id');
    }

    /**
     * Get the post that owns the comment.
     */
    public function creator(): BelongsTo {
        return $this->belongsTo('App\Models\User', 'creator_id', 'id');
    }


}

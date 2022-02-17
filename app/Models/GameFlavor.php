<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class GameFlavor extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'game_flavor';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'lang_id', 'interface_lang_id', 'description',
        'creator_id', 'cover_img_id', 'game_version_id', 'submitted_for_approval',
        'allow_clone', 'copyright_link', 'is_built', 'game_identifier'];

    /**
     * Get the Equivalence Sets for the card.
     */
    public function equivalenceSets(): HasMany {
        return $this->hasMany('App\Models\EquivalenceSet', 'flavor_id', 'id');
    }

    public function gameRequests(): HasMany {
        return $this->hasMany('App\Models\GameRequest', 'game_flavor_id', 'id');
    }

    public function resourceFiles(): HasMany {
        return $this->hasMany('App\Models\ResourceFile', 'game_flavor_id', 'id');
    }
    /**
     * Get the Equivalence Sets for the card.
     */
    public function reports(): HasMany {
        return $this->hasMany('App\Models\GameFlavorReport');
    }

    /**
     * Get the images for the card.
     */
    public function sounds(): HasMany {
        return $this->hasMany('App\Models\Resource');
    }

    public function gameVersion(): HasOne {
        return $this->hasOne('App\Models\GameVersion', 'id', 'game_version_id');
    }

    public function language(): HasOne {
        return $this->hasOne('App\Models\Language', 'id', 'lang_id');
    }

    public function interfaceLanguage(): HasOne {
        return $this->hasOne('App\Models\Language', 'id', 'interface_lang_id');
    }

    /**
     * Get the post that owns the comment.
     */
    public function creator(): BelongsTo {
        return $this->belongsTo('App\Models\User', 'creator_id', 'id');
    }

    /**
     * Get the post that owns the comment.
     */
    public function coverImg(): HasOne {
        return $this->hasOne('App\Models\Resource', 'id', 'cover_img_id');
    }

    /***
     * https://laravel.com/docs/5.3/eloquent-mutators#accessors-and-mutators
     *
     * @param $value
     * @return mixed
     */
    public function getAccessedByUserAttribute($value)
    {
        return $value;
    }

    public function setAccessedByUserAttribute($value)
    {
        $this->attributes['accessed_by_user'] = $value;
    }

    public function getCoverImgFilePathAttribute($value)
    {
        return $value;
    }

    public function setCoverImgFilePathAttribute($value)
    {
        $this->attributes['cover_img_file_path'] = $value;
    }

}

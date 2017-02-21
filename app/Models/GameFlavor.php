<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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
    protected $fillable = ['name', 'lang_id', 'interface_lang_id', 'description', 'creator_id', 'cover_img_id', 'game_version_id'];

    /**
     * Get the Equivalence Sets for the card.
     */
    public function equivalenceSets() {
        return $this->hasMany('App\Models\EquivalenceSet');
    }

    /**
     * Get the images for the card.
     */
    public function sounds() {
        return $this->hasMany('App\Models\Resource');
    }

    public function gameVersion() {
        return $this->hasOne('App\Models\GameVersion', 'id', 'game_version_id');
    }

    public function language() {
        return $this->hasOne('App\Models\Language', 'id', 'lang_id');
    }

    public function interfaceLanguage() {
        return $this->hasOne('App\Models\Language', 'id', 'interface_lang_id');
    }

    /**
     * Get the post that owns the comment.
     */
    public function creator()
    {
        return $this->belongsTo('App\Models\User', 'creator_id', 'id');
    }

    /**
     * Get the post that owns the comment.
     */
    public function coverImg()
    {
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

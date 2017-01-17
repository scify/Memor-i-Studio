<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResourceCategory extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'resource_category';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['path', 'description', 'game_version_id'];

    /**
     * Get the @see GameVersion this resource category belongs to.
     */
    public function gameVersion()
    {
        return $this->hasOne('App\Models\GameVersion', 'id', 'game_version_id');
    }

    /**
     * Get the @see GameVersion this resource category belongs to.
     */
    public function resources()
    {
        return $this->hasMany('App\Models\Resource', 'category_id', 'id')->orderBy('name');
    }
}

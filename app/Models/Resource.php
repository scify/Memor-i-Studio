<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resource extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'resource';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['category_id', 'name', 'file_path', 'default_text'];

    public function resourceCategory() {
        return $this->hasOne('App\Models\ResourceCategory', 'id', 'category_id');
    }

    /**
     * Get the translations of this resource
     */
    public function translations()
    {
        return $this->hasMany('App\Models\ResourceTranslation', 'resource_id', 'id');
    }
}

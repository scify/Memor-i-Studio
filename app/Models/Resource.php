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
    protected $fillable = ['category_id', 'name', 'default_text'];

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

    public function file() {
        return $this->hasOne('App\Models\ResourceFile', 'resource_id', 'id');
    }

    /***
     * https://laravel.com/docs/5.3/eloquent-mutators#accessors-and-mutators
     *
     * @param $value
     * @return mixed
     */
    public function getFilePathAttribute($value)
    {
        return $value;
    }

    public function setFilePathAttribute($value)
    {
        $this->attributes['file_path'] = $value;
    }
}

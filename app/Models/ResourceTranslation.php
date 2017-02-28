<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResourceTranslation extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'resource_translation';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['resource_name', 'resource_description', 'lang_id', 'resource_id'];

    /**
     * Get the @see Resource this translation belongs to.
     */
    public function resource() {
        return $this->belongsTo('App\Models\Resource', 'resource_id', 'id');
    }

    /**
     * Get the @see this translation belongs to.
     */
    public function language() {
        return $this->belongsTo('App\Models\Language', 'lang_id', 'id');
    }
}

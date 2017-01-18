<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResourceCategoryTranslation extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'resource_category_translation';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['description', 'lang_id', 'resource_category_id'];

    /**
     * Get the @see Resource this translation belongs to.
     */
    public function resourceCategory() {
        return $this->belongsTo('App\Models\ResourceCategory', 'resource_category_id', 'id');
    }

    /**
     * Get the @see this translation belongs to.
     */
    public function language() {
        return $this->belongsTo('App\Models\Language', 'lang_id', 'id');
    }
}

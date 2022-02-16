<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class ResourceCategory extends Model {
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
    protected $fillable = ['path', 'description', 'game_version_id', 'type_id', 'order_id'];

    /**
     * Get the @see GameVersion this resource category belongs to.
     */
    public function gameVersion(): HasOne {
        return $this->hasOne('App\Models\GameVersion', 'id', 'game_version_id');
    }

    /**
     * Get the @see GameVersion this resource category belongs to.
     */
    public function resources(): HasMany {
        return $this->hasMany('App\Models\Resource', 'category_id', 'id')
            ->orderBy(DB::raw('-`order_id`'), 'desc');
    }

    /**
     * Get the @see GameVersion this resource category belongs to.
     */
    public function categoryType(): HasOne {
        return $this->hasOne('App\Models\ResourceCategoryType', 'id', 'type_id');
    }

    public function translations(): HasMany {
        return $this->hasMany('App\Models\ResourceCategoryTranslation', 'resource_category_id', 'id');
    }
}

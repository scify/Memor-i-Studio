<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResourceFile extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'resource_file';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['file_path', 'game_flavor_id', 'resource_id'];

    /**
     * Get the @see Resource this translation belongs to.
     */
    public function resource() {
        return $this->belongsTo('App\Models\Resource', 'resource_id', 'id');
    }

    /**
     * Get the @see this translation belongs to.
     */
    public function gameFlavor() {
        return $this->belongsTo('App\Models\GameFlavor', 'game_flavor_id', 'id');
    }
}

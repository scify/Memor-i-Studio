<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Card extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'card';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['label', 'description_sound'];

    /**
     * Get the images for the card.
     */
    public function images()
    {
        return $this->hasMany('App\Models\Image');
    }

    /**
     * Get the images for the card.
     */
    public function sounds()
    {
        return $this->hasMany('App\Models\Sound');
    }
}

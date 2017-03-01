<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EquivalenceSet extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'equivalence_set';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'flavor_id', 'description_sound_id', 'description_sound_probability'];

    /**
     * Get the first image for the card.
     */
    public function cards()
    {
        return $this->hasMany('App\Models\Card');
    }

    /**
     * Get the first image for the card.
     */
    public function gameFlavor()
    {
        return $this->hasOne('App\Models\GameFlavor', 'flavor_id', 'id');
    }

    /**
     * Get the description sound for the equivalence set.
     */
    public function descriptionSound()
    {
        return $this->hasOne('App\Models\Resource', 'id', 'description_sound_id');
    }
}

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
    protected $fillable = ['label', 'category', 'unique', 'image_id', 'negative_image_id', 'sound_id', 'equivalence_set_id'];

    /**
     * Get the first image for the card.
     */
    public function image()
    {
        return $this->hasOne('App\Models\Image', 'id', 'image_id');
    }

    /**
     * Get the second (possible negative) image for the card
     */
    public function secondImage()
    {
        return $this->hasOne('App\Models\Image', 'id', 'negative_image_id');
    }

    /**
     * Get the card sound.
     */
    public function sound()
    {
        return $this->hasOne('App\Models\Sound', 'sound_id', 'id');
    }

    /**
     * Get the Equivalence Set this card belongs to.
     */
    public function equivalenceSet()
    {
        return $this->hasOne('App\Models\EquivalenceSet', 'equivalence_set_id', 'id');
    }



}

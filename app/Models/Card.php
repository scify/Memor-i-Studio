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
    protected $fillable = ['label', 'image_id', 'negative_image_id', 'sound_id', 'game_version_id', 'equivalent_card_id'];

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
     * Get the game version this card belongs to.
     */
    public function gameVersion()
    {
        return $this->hasOne('App\Models\GameVersion', 'game_version_id', 'id');
    }

    public function equivalentCard() {
        return $this->hasOne('App\Models\Card', 'equivalent_card_id', 'id');
    }


}

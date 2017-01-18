<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CardResource extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'card_resource';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['card_id', 'file_path'];

    public function card() {
        return $this->belongsTo('App\Models\Card', 'card_id', 'id');
    }

}

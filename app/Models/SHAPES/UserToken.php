<?php

namespace App\Models\SHAPES;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserToken extends Model {
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'user_shapes_tokens';

    protected $fillable = [
        'id',
        'user_id',
        'token'
    ];

    public function creator(): HasOne {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}

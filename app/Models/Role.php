<?php
/**
 * Created by IntelliJ IDEA.
 * User: pisaris
 * Date: 29/12/2016
 * Time: 10:20 πμ
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Role extends Model {
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'role';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'name', 'description'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function users()
    {
        return $this->belongsTo('App\User', 'role_id', 'id');
    }
}
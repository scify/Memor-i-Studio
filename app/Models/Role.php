<?php
/**
 * Created by IntelliJ IDEA.
 * User: pisaris
 * Date: 29/12/2016
 * Time: 10:20 πμ
 */

namespace App\Models;


class Role {
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
    protected $fillable = ['name', 'description'];
}
<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Notifications\Notifiable;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {
    use Authenticatable, CanResetPassword, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role_id', 'hashed_email', 'logout',
        'shapes_auth_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function role() {
        return $this->hasOne('App\Models\Role', 'id', 'role_id');
    }

    /**
     * Get game versions this user has created
     */
    public function gameVersions() {
        return $this->hasMany('App\Models\GameFlavor', 'creator_id');
    }

    /**
     * Checks if the user has one of the admin roles.
     *
     * @return bool
     */
    public function isAdmin() {

        if ($this->has('role') and ($this->role->id == 2)) {
            return true;
        }

        return false;
    }
}

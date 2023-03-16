<?php

namespace App\BusinessLogicLayer\managers;

use Illuminate\Support\Facades\Gate;

class UserRoleManager {

    public function registerUserPolicies() {
        Gate::define('manage-platform', function ($user) {
            return $user->isAdmin();
        });
    }

}
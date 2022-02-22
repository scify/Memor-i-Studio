<?php

namespace App\StorageLayer;

use App\Models\User;

class UserRepository extends Repository {

    function getModelClassName(): string {
        return User::class;
    }

    public function create(array $data) {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }
}

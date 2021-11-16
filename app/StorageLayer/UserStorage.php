<?php

namespace App\StorageLayer;

use App\Models\User;

class UserStorage {

    public function get(int $id) {
        return User::findOrFail($id);
    }

}

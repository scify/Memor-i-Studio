<?php

namespace App\StorageLayer\SHAPES;

use App\Models\SHAPES\UserToken;
use App\StorageLayer\Repository;

class UserTokensRepository extends Repository {

    /**
     * @inheritDoc
     */
    function getModelClassName(): string {
        return UserToken::class;
    }
}

<?php
/**
 * Created by IntelliJ IDEA.
 * User: pisaris
 * Date: 5/1/2017
 * Time: 12:27 μμ
 */

namespace App\StorageLayer;


use App\Models\Card;

class CardStorage {

    public function getCardsForGameVersion($gameVersionId) {
        return Card::where([
            ['game_version_id', '=', $gameVersionId]
        ])->get()->sortByDesc("created_at");
    }
}
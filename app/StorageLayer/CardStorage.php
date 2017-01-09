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

    public function saveCard(Card $card) {
        $card->save();
        return $card;
    }
}
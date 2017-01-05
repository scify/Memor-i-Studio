<?php
/**
 * Created by IntelliJ IDEA.
 * User: pisaris
 * Date: 5/1/2017
 * Time: 12:25 μμ
 */

namespace App\BusinessLogicLayer\managers;


use App\StorageLayer\CardStorage;

class CardManager {

    private $cardStorage;

    /**
     * CardController constructor.
     */
    public function __construct() {
        $this->cardStorage = new CardStorage();
    }

    public function getCardsForGameVersion($gameVersionId) {
        return $this->cardStorage->getCardsForGameVersion($gameVersionId);
    }
}
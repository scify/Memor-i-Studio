<?php
/**
 * Created by IntelliJ IDEA.
 * User: pisaris
 * Date: 5/1/2017
 * Time: 12:25 μμ
 */

namespace App\BusinessLogicLayer\managers;


use App\Models\Card;
use App\StorageLayer\CardStorage;

class CardManager {

    private $cardStorage;
    private $imgManager;
    private $soundManager;

    /**
     * CardController constructor.
     */
    public function __construct() {
        $this->cardStorage = new CardStorage();
        $this->imgManager = new ImgManager();
        $this->soundManager = new SoundManager();
    }

    public function getCardsForGameVersion($gameVersionId) {
        return $this->cardStorage->getCardsForGameVersion($gameVersionId);
    }

    public function createNewCard($input) {
        $newCard = new Card();
        $newCard->label = $this->generateRandomString();
        $newCard->image_id = $this->imgManager->uploadCardImg($input['image']);
        if($input['negative_image'] != null)
            $newCard->image_id = $this->imgManager->uploadCardImg($input['negative_image']);
        $newCard->game_version_id = $input['game_version_id'];
        $newCard->sound_id = $this->soundManager->uploadCardSound($input['sound']);
        return $this->cardStorage->saveCard($newCard);
    }


    private function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
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

//    public function getCardsForGameFlavor($gameVersionId) {
//        return $cards = $this->cardStorage->getCardsForGameFlavor($gameVersionId);
//    }

    public function createNewCard($input) {
        //dd($input);
        $newCard = new Card();
        $newCard->label = $this->generateRandomString();
        $newCard->image_id = $this->imgManager->uploadCardImg($input['image']);
        if(isset($input['negative_image'])) {
            if ($input['negative_image'] != null)
                $newCard->negative_image_id = $this->imgManager->uploadCardImg($input['negative_image']);
        }

        $newCard->sound_id = $this->soundManager->uploadCardSound($input['sound']);
        return $this->cardStorage->saveCard($newCard);
    }


    /**
     * Generates and returns a random string
     *
     * @param int $length the length of the string
     * @return string the random string generated
     */
    private function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function associateCards(array $createdCards) {
        //if only one card, it is associated with itself
        if(count($createdCards) == 1) {
            $this->associateCardWithItself($createdCards[0]);
        } else {
            //if many cards, associate each card with the next one
            $this->associateCardsArray($createdCards);
        }
    }

//    private function associateCardsArray(array $createdCards) {
//        //for each card, we set as equivalent the current card with the next one.
//        // The last card is associated with it's previous.
//        foreach ($createdCards as $card) {
//            $nextCard = next($createdCards);
//            if($nextCard != null) {
//                $card->equivalent_card_id = $nextCard->id;
//                $this->cardStorage->saveCard($card);
//            }
//        }
//    }
//
//    /**
//     * Sets a card as aquivalent with itself
//     *
//     * @param Card $card the card that will be associated
//     */
//    private function associateCardWithItself(Card $card) {
//        $card->equivalent_card_id = $card->id;
//        $this->cardStorage->saveCard($card);
//    }

}
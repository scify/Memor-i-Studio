<?php
/**
 * Created by IntelliJ IDEA.
 * User: pisaris
 * Date: 5/1/2017
 * Time: 12:25 μμ
 */

namespace App\BusinessLogicLayer\managers;


use App\Models\Card;
use App\Models\EquivalenceSet;
use App\Models\GameFlavor;
use App\StorageLayer\CardStorage;
use League\Flysystem\Exception;

include_once 'functions.php';

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

    /**
     * Adds an array of @see Card objects to a given @see EquivalentSet instance
     *
     * @param array $cards the array of @see Card cards
     * @param EquivalenceSet $equivalenceSet  the set that these cards will belong
     */
    public function addCardsToEquivalenceSet(array $cards, EquivalenceSet $equivalenceSet) {
        foreach ($cards as $createdCard) {
            $createdCard->equivalence_set_id = $equivalenceSet->id;
            $this->cardStorage->saveCard($createdCard);
        }
    }

    public function createCards(EquivalenceSet $newEquivalenceSet, array $input) {
        $index = 1;
        foreach ($input['card'] as $cardFields) {
            $newCard = $this->createNewCard($cardFields, $newEquivalenceSet->id, $index % 2 == 1 ? 'item' : 'item_equivalent');
            $index++;
            if ($newCard == null) {
                throw new Exception('Card creation failed');
            }
        }
        //TODO: discuss with ggianna
        if(count($input['card']) == 1) {
            $newCard = $this->createNewCard($input['card'][1], $newEquivalenceSet->id, 'item_equivalent');
            if ($newCard == null) {
                throw new Exception('Card creation failed');
            }
        }
    }

    /**
     * Creates a new @see Card instance and assignes values to it, as well as its @see EquivalenceSet
     *
     * @param $input array an associative array with the card data
     * @param $equivalenceSetId int the id of the @see EquivalenceSet the card will belong.
     * @param $category string the category this card will be assigned with
     * @return Card the newly created card
     */
    public function createNewCard($input, $equivalenceSetId, $category) {
        //dd($input);
        $newCard = new Card();
        $newCard->label = generateRandomString();
        $newCard->image_id = $this->imgManager->uploadCardImg($input['image']);
        $newCard->equivalence_set_id = $equivalenceSetId;
        $newCard->category = $category;
        if(isset($input['negative_image'])) {
            if ($input['negative_image'] != null)
                $newCard->negative_image_id = $this->imgManager->uploadCardImg($input['negative_image']);
        }

        $newCard->sound_id = $this->soundManager->uploadCardSound($input['sound']);
        return $this->cardStorage->saveCard($newCard);
    }

    /**
     * Finds and assigns new values to a @see Card instance.
     *
     * @param array $input an associative array with the card data
     * @return Card the just edited Card
     */
    public function editCard(array $input) {
        $cardFields = $input['card'][1];
        $cardToBeEdited = $this->cardStorage->getCardById($input['cardId']);
        if(isset($cardFields['image']))
            $cardToBeEdited->image_id = $this->imgManager->uploadCardImg($cardFields['image']);
        if(isset($cardFields['negative_image']))
            $cardToBeEdited->negative_image_id = $this->imgManager->uploadCardImg($cardFields['negative_image']);
        if(isset($cardFields['sound']))
            $cardToBeEdited->sound_id = $this->soundManager->uploadCardSound($cardFields['sound']);

        return $this->cardStorage->saveCard($cardToBeEdited);
    }

    /**
     * Each @see GameFlavor has a set of equivalence sets.
     * Each of these sets contains a set of @see Card instances.
     *
     * @param $gameFlavorId
     * @return array
     */
    public function getCardsForGameFlavor($gameFlavorId) {
        $equivalenceSetManager = new EquivalenceSetManager();
        $equivalenceSets = $equivalenceSetManager->getEquivalenceSetsForGameFlavor($gameFlavorId);

        $cards = array();
        foreach ($equivalenceSets as $equivalenceSet) {
            foreach ($equivalenceSet->cards as $card) {
                $card->imageObj = $card->image;
                $card->negativeImageObj = $card->secondImage;
                $card->imgPath = url('data/images/' . $card->image->imageCategory->category .  '/' . $card->image->file_path);
                if($card->secondImage != null)
                    $card->negativeImgPath = url('data/images/' . $card->secondImage->imageCategory->category .  '/' . $card->secondImage->file_path);
                $card->soundObj = $card->sound;
                array_push($cards, $card);
            }
        }
        return $cards;
    }


}
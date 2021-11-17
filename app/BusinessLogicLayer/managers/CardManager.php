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
    private $resourceManager;
    /**
     * CardController constructor.
     */
    public function __construct(CardStorage $cardStorage, ImgManager $imgManager,
                                SoundManager $soundManager, ResourceManager $resourceManager) {
        $this->cardStorage = $cardStorage;
        $this->imgManager = $imgManager;
        $this->soundManager = $soundManager;
        $this->resourceManager= $resourceManager;
    }

    /**
     * Adds an array of @param array $cards the array of @see Card cards
     * @param EquivalenceSet $equivalenceSet the set that these cards will belong
     * @see Card objects to a given @see EquivalentSet instance
     *
     */
    public function addCardsToEquivalenceSet(array $cards, EquivalenceSet $equivalenceSet) {
        foreach ($cards as $createdCard) {
            $createdCard->equivalence_set_id = $equivalenceSet->id;
            $this->cardStorage->saveCard($createdCard);
        }
    }

    public function createCards($gameFlavorId, EquivalenceSet $newEquivalenceSet, array $input) {
        $index = 1;
        $cardLabel = generateRandomString();
        foreach ($input['card'] as $cardFields) {
            $newCard = $this->createNewCard($gameFlavorId, $cardLabel, $cardFields, $newEquivalenceSet->id, $index % 2 == 1 ? 'item' : 'item_equivalent');
            $index++;
            if ($newCard == null) {
                throw new Exception('Card creation failed');
            }
        }
        if (count($input['card']) == 1) {
            $newCard = $this->createNewCard($gameFlavorId, $cardLabel, $input['card'][1], $newEquivalenceSet->id, 'item_equivalent');
            if ($newCard == null) {
                throw new Exception('Card creation failed');
            }
        }
    }

    /**
     * Creates a new @param $gameFlavorId int the id of the game flavor tha card belongs to
     * @param $cardLabel string a random string serving as card label (debugging purposes only)
     * @param $input array an associative array with the card data
     * @param $equivalenceSetId int the id of the @see EquivalenceSet the card will belong.
     * @param $category string the category this card will be assigned with
     * @return Card the newly created card
     * @see Card instance and assignes values to it, as well as its @see EquivalenceSet
     *
     */
    public function createNewCard($gameFlavorId, $cardLabel, $input, $equivalenceSetId, $category) {
        //dd($input);
        $newCard = new Card();
        $newCard->label = $cardLabel;

        $newCard->equivalence_set_id = $equivalenceSetId;
        $newCard->category = $category;

        $newCard = $this->cardStorage->saveCard($newCard);
        $newCard->image_id = $this->imgManager->uploadCardImg($gameFlavorId, $input['image']);
        $newCard->sound_id = $this->soundManager->uploadCardSound($gameFlavorId, $input['sound']);
        if (isset($input['negative_image'])) {
            if ($input['negative_image'] != null)
                $newCard->negative_image_id = $this->imgManager->uploadCardImg($gameFlavorId, $input['negative_image']);
        }
        return $this->cardStorage->saveCard($newCard);
    }

    /**
     * Finds and assigns new values to a @param $gameFlavorId int the id of the game flavor this card belongs to
     * @param array $input an associative array with the card data
     * @return Card the just edited Card
     * @see Card instance.
     *
     */
    public function editCard($gameFlavorId, array $input) {
        $cardFields = $input['card'][1];
        $cardToBeEdited = $this->cardStorage->getCardById($input['cardId']);
        if (isset($cardFields['image']))
            $cardToBeEdited->image_id = $this->imgManager->uploadCardImg($gameFlavorId, $cardFields['image']);
        if (isset($cardFields['negative_image']))
            $cardToBeEdited->negative_image_id = $this->imgManager->uploadCardImg($gameFlavorId, $cardFields['negative_image']);
        if (isset($cardFields['sound']))
            $cardToBeEdited->sound_id = $this->soundManager->uploadCardSound($gameFlavorId, $cardFields['sound']);

        return $this->cardStorage->saveCard($cardToBeEdited);
    }

    /**
     * Each @param $gameFlavorId
     * @return array
     * @see GameFlavor has a set of equivalence sets.
     * Each of these sets contains a set of @see Card instances.
     *
     */
    public function getCardsForGameFlavor($gameFlavorId) {
        $equivalenceSetManager = new EquivalenceSetManager();
        $equivalenceSets = $equivalenceSetManager->getEquivalenceSetsViewModelsForGameFlavor($gameFlavorId);

        $cards = array();
        foreach ($equivalenceSets as $equivalenceSet) {
            foreach ($equivalenceSet->cards as $card) {
                //dd($card);
                if ($card->image != null) {
                    $card->imageObj = $card->image->file;
                    $card->imgPath = url('resolveData/' . $card->image->file->file_path);
                }

                if ($card->secondImage != null) {
                    $card->negativeImageObj = $card->secondImage->file;
                    $card->negativeImgPath = url('resolveData/' . $card->secondImage->file->file_path);
                }
                if ($card->sound != null)
                    $card->soundObj = $card->sound->file;
                array_push($cards, $card);
            }
        }
        return $cards;

    }

    public function cloneCardsForEquivalenceSet(EquivalenceSet $equivalenceSet, EquivalenceSet $newEquivalenceSet, $gameFlavorId, $newGameFlavorId) {
        foreach ($equivalenceSet->cards as $card) {
            $newCard = $card->replicate();
            $newCard->equivalence_set_id = $newEquivalenceSet->id;
            $newCard->save();
            $newImageResource = $this->resourceManager->cloneResource($card->image, $gameFlavorId, $newGameFlavorId);
            $newCard->image_id = $newImageResource->id;
            if ($card->secondImage != null) {
                $newSecondImageResource = $this->resourceManager->cloneResource($card->secondImage, $gameFlavorId, $newGameFlavorId);
                $newCard->negative_image_id = $newSecondImageResource->id;
            }
            $newSoundResource = $this->resourceManager->cloneResource($card->sound, $gameFlavorId, $newGameFlavorId);
            $newCard->sound_id = $newSoundResource->id;
            $newCard->save();
        }
    }


}

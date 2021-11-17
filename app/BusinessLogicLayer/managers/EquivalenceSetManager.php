<?php
/**
 * Created by IntelliJ IDEA.
 * User: pisaris
 * Date: 9/1/2017
 * Time: 3:20 μμ
 */

namespace App\BusinessLogicLayer\managers;


use App\Models\EquivalenceSet;
use App\StorageLayer\EquivalentSetStorage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

include_once 'functions.php';

class EquivalenceSetManager {

    private $equivalenceSetStorage;
    private $soundManager;
    private $cardManager;
    private $equivalenceSetViewModelProvider;

    public function __construct(EquivalentSetStorage            $equivalenceSetStorage, SoundManager $soundManager,
                                EquivalenceSetViewModelProvider $equivalenceSetViewModelProvider,
                                CardManager                     $cardManager) {
        $this->equivalenceSetStorage = $equivalenceSetStorage;
        $this->soundManager = $soundManager;
        $this->equivalenceSetViewModelProvider = $equivalenceSetViewModelProvider;
        $this->cardManager = $cardManager;
    }

    public function getEquivalenceSetsForGameFlavor($gameFlavorId) {
        return $this->equivalenceSetStorage->getEquivalenceSetsForGameFlavor($gameFlavorId);
    }

    public function createEquivalenceSet($gameFlavorId, array $input) {
        $newEquivalenceSet = new EquivalenceSet();
        $newEquivalenceSet->name = generateRandomString();
        $newEquivalenceSet->flavor_id = $gameFlavorId;
        if (isset($input['equivalence_set_description_sound'])) {
            if ($input['equivalence_set_description_sound'] != null) {
                $newEquivalenceSet->description_sound_id = $this->soundManager->uploadEquivalenceSetDescriptionSound($gameFlavorId, $input['equivalence_set_description_sound']);
                if (isset($input['equivalence_set_description_sound_probability'])) {
                    $newEquivalenceSet->description_sound_probability = $input['equivalence_set_description_sound_probability'];
                }
            }
        }
        return $this->equivalenceSetStorage->saveEquivalenceSet($newEquivalenceSet);
    }

    public function deleteSet($id) {
        $this->equivalenceSetStorage->deleteSet($id);
    }

    /**
     * Prepares the JSON file describing the equivalence sets
     *
     * @param $gameFlavorId int the id of the @see GameFlavor
     * @return string
     */
    public function createEquivalenceSetsJSONFile($gameFlavorId) {
        $equivalenceSets = $this->equivalenceSetViewModelProvider->getEquivalenceSetsViewModelsForGameFlavor($gameFlavorId);
        //dd($equivalenceSets);
        $equivalence_card_sets = array();
        $equivalence_card_sets['equivalence_card_sets'] = array();
        foreach ($equivalenceSets as $equivalenceSet) {
            $cards = array();

            foreach ($equivalenceSet->cards as $card) {
                $current_card = array();
                $current_card['label'] = $card->label;
                $current_card['category'] = $card->category;
                $current_card['unique'] = $card->unique;
                $current_card['sounds'] = array();
                $current_card['images'] = array();
                $current_card['description_sound'] = "";
                $current_card['equivalenceCardSetHashCode'] = "";
                if ($equivalenceSet->descriptionSound != null) {
                    $fileName = substr($equivalenceSet->descriptionSound->file->file_path, strrpos($equivalenceSet->descriptionSound->file->file_path, '/') + 1);
                    $current_card['description_sound'] = $fileName;
                    $current_card['description_sound_probability'] = $equivalenceSet->description_sound_probability;
                }
                if ($card->sound != null) {
                    $fileName = substr($card->sound->file->file_path, strrpos($card->sound->file->file_path, '/') + 1);
                    array_push($current_card['sounds'], $fileName);
                }
                if ($card->image != null) {
                    $fileName = substr($card->image->file->file_path, strrpos($card->image->file->file_path, '/') + 1);
                    array_push($current_card['images'], $fileName);
                }
                if ($card->secondImage != null) {
                    $fileName = substr($card->secondImage->file->file_path, strrpos($card->secondImage->file->file_path, '/') + 1);
                    array_push($current_card['images'], $fileName);
                }

                array_push($cards, $current_card);
            }
            array_push($equivalence_card_sets['equivalence_card_sets'], $cards);
        }
        $pathRelative = 'data_packs/additional_pack_' . $gameFlavorId . '/data/json_DB/equivalence_cards_sets.json';

        $filePath = storage_path() . '/app/' . $pathRelative;
        if (File::exists($filePath)) {
            File::delete($filePath);
        }

        Storage::put($pathRelative, json_encode($equivalence_card_sets));

        return json_encode($equivalence_card_sets);
    }

    public function cloneEquivalenceSetsAndCardsForGameFlavor($gameFlavorId, $newGameFlavorId) {
        $equivalenceSetsForGameFlavor = $this->getEquivalenceSetsForGameFlavor($gameFlavorId);
        foreach ($equivalenceSetsForGameFlavor as $equivalenceSet) {
            $newEquivalenceSet = $equivalenceSet->replicate();
            $newEquivalenceSet->flavor_id = $newGameFlavorId;
            $newEquivalenceSet->save();
            $this->cardManager->cloneCardsForEquivalenceSet($equivalenceSet, $newEquivalenceSet, $gameFlavorId, $newGameFlavorId);
        }
    }

    public function editEquivalenceSet($equivalenceSetId, array $input) {
        $equivalenceSet = $this->getEquivalenceSet($equivalenceSetId);
        if (isset($input['equivalence_set_description_sound'])) {
            if ($input['equivalence_set_description_sound'] != null) {
                $equivalenceSet->description_sound_id = $this->soundManager->uploadEquivalenceSetDescriptionSound($equivalenceSet->flavor_id, $input['equivalence_set_description_sound']);
            }
        }
        if (isset($input['equivalence_set_description_sound_probability'])) {
            if ($input['equivalence_set_description_sound_probability'] != null) {
                $equivalenceSet->description_sound_probability = $input['equivalence_set_description_sound_probability'];
            }
        }
        return $this->equivalenceSetStorage->saveEquivalenceSet($equivalenceSet);
    }

    private function getEquivalenceSet($equivalenceSetId) {
        return $this->equivalenceSetStorage->getEquivalenceSetById($equivalenceSetId);
    }
}

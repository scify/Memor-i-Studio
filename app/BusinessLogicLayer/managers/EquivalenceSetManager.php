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

class EquivalenceSetManager {

    public function __construct() {
        $this->equivalenceSetStorage = new EquivalentSetStorage();
    }

    public function getEquivalenceSetsForGameFlavor($gameFlavorId) {
        return $this->equivalenceSetStorage->getEquivalenceSetsForGameFlavor($gameFlavorId);
    }

    public function createEquivalenceSet($gameFlavorId) {
        $newEquivalenceSet = new EquivalenceSet();
        $newEquivalenceSet->name = $this->generateRandomString();
        $newEquivalenceSet->flavor_id = $gameFlavorId;
        return $this->equivalenceSetStorage->saveEquivalenceSet($newEquivalenceSet);
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

    public function deleteSet($id) {
        $this->equivalenceSetStorage->deleteSet($id);
    }
}
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

include_once 'functions.php';

class EquivalenceSetManager {

    private $equivalenceSetStorage;

    public function __construct() {
        $this->equivalenceSetStorage = new EquivalentSetStorage();
    }

    public function getEquivalenceSetsForGameFlavor($gameFlavorId) {
        return $this->equivalenceSetStorage->getEquivalenceSetsForGameFlavor($gameFlavorId);
    }

    public function createEquivalenceSet($gameFlavorId) {
        $newEquivalenceSet = new EquivalenceSet();
        $newEquivalenceSet->name = generateRandomString();
        $newEquivalenceSet->flavor_id = $gameFlavorId;
        return $this->equivalenceSetStorage->saveEquivalenceSet($newEquivalenceSet);
    }

    public function deleteSet($id) {
        $this->equivalenceSetStorage->deleteSet($id);
    }
}
<?php
/**
 * Created by IntelliJ IDEA.
 * User: pisaris
 * Date: 9/1/2017
 * Time: 3:20 μμ
 */

namespace App\BusinessLogicLayer\managers;


use App\StorageLayer\EquivalentSetStorage;

class EquivalenceSetManager {

    public function __construct() {
        $this->equivalenceSetStorage = new EquivalentSetStorage();
    }

    public function getEquivalenceSetsForGameFlavor($gameFlavorId) {
        return $this->equivalenceSetStorage->getEquivalenceSetsForGameFlavor($gameFlavorId);
    }
}
<?php

namespace App\BusinessLogicLayer\managers;

use App\StorageLayer\EquivalentSetStorage;

class EquivalenceSetViewModelProvider {

    private $equivalenceSetStorage;

    public function __construct(EquivalentSetStorage $equivalenceSetStorage) {
        $this->equivalenceSetStorage = $equivalenceSetStorage;
    }

    public function getEquivalenceSetsViewModelsForGameFlavor($gameFlavorId) {
        $equivalenceSets = $this->equivalenceSetStorage->getEquivalenceSetsForGameFlavor($gameFlavorId);
        foreach ($equivalenceSets as $equivalenceSet) {
            if ($equivalenceSet->descriptionSound != null) {
                $equivalenceSet->descriptionSoundPath = url('resolveData/' . $equivalenceSet->descriptionSound->file->file_path);
            }
        }
        return $equivalenceSets;
    }

}

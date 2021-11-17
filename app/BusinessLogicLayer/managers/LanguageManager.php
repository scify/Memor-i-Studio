<?php

namespace App\BusinessLogicLayer\managers;

use App\StorageLayer\LanguageStorage;

class LanguageManager {
    private $languagesStorage;

    public function __construct(LanguageStorage $languagesStorage) {
        // initialize $userStorage
        $this->languagesStorage = $languagesStorage;
    }

    public function getAvailableLanguages() {
        return $this->languagesStorage->getAllLanguages();
    }

    public function getLanguage($langId) {
        return $this->languagesStorage->getLanguageById($langId);
    }
}

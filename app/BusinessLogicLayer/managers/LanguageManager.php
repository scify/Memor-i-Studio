<?php

namespace App\BusinessLogicLayer\managers;

use App\StorageLayer\LanguageStorage;

class LanguageManager {
    private $languagesStorage;

    public function __construct() {
        // initialize $userStorage
        $this->languagesStorage = new LanguageStorage();
    }

    public function getAvailableLanguages() {
        return $this->languagesStorage->getAllLanguages();
    }
}
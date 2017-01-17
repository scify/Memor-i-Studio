<?php

namespace App\BusinessLogicLayer\managers;

use App\Models\GameVersionLanguage;
use App\StorageLayer\GameVersionLanguageStorage;

class GameVersionLanguageManager {
    private $gameVersionLanguageStorage;

    public function __construct() {
        $this->gameVersionLanguageStorage = new GameVersionLanguageStorage();
    }

    public function getGameVersionLanguages($id) {
        return $this->gameVersionLanguageStorage->getGameVersionLanguages($id);
    }

    public function gameVersionHasLanguage($gameVersionId, $langId) {
        $gameVersionLanguage = $this->gameVersionLanguageStorage->getLanguageForGameVersion($gameVersionId, $langId);
        if($gameVersionLanguage == null)
            return false;
        return true;
    }

    public function addGameVersionLanguage($gameVersionId, $langId) {
        $gameVersionLanguage = new GameVersionLanguage();
        $gameVersionLanguage->lang_id = $langId;
        $gameVersionLanguage->game_version_id = $gameVersionId;
        $this->gameVersionLanguageStorage->storeGameVersionLanguage($gameVersionLanguage);
    }
}
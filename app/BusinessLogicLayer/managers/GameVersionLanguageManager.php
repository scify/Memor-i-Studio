<?php

namespace App\BusinessLogicLayer\managers;

use App\Models\GameVersionLanguage;
use App\StorageLayer\GameVersionLanguageStorage;
use Illuminate\Database\Eloquent\Collection;

class GameVersionLanguageManager {
    private $gameVersionLanguageStorage;
    private $languageManager;

    public function __construct() {
        $this->gameVersionLanguageStorage = new GameVersionLanguageStorage();
        $this->languageManager = new LanguageManager();
    }

    public function getGameVersionLanguages($id) {
        $gameVersionLanguages =  $this->gameVersionLanguageStorage->getGameVersionLanguages($id);
        $languages = new Collection();
        foreach ($gameVersionLanguages as $gameVersionLanguage) {
            $languages->add($this->languageManager->getLanguage($gameVersionLanguage->lang_id));
        }
        return $languages;
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
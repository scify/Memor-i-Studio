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

    /**
     * Gets the languages the game version has
     *
     * @param $gameVersionId int the id of the game version
     * @return Collection a set of @see Language instances
     */
    public function getGameVersionLanguages($gameVersionId) {
        $gameVersionLanguages =  $this->gameVersionLanguageStorage->getGameVersionLanguages($gameVersionId);
        $languages = new Collection();
        foreach ($gameVersionLanguages as $gameVersionLanguage) {
            $languages->add($this->languageManager->getLanguage($gameVersionLanguage->lang_id));
        }
        return $languages;
    }

    public function getFirstLanguageAvailableForGameVersion($gameVersionId) {
        return $this->gameVersionLanguageStorage->getFirstLanguageAvailableForGameVersion($gameVersionId);
    }

    /**
     * Checks if a given game version has a specified language
     *
     * @param $gameVersionId int the id of the game version
     * @param $langId int the id of the language
     * @return bool true if the game version has the selected language, otherwise false
     */
    public function gameVersionHasLanguage($gameVersionId, $langId) {
        $gameVersionLanguage = $this->gameVersionLanguageStorage->getLanguageForGameVersion($gameVersionId, $langId);
        if($gameVersionLanguage == null)
            return false;
        return true;
    }

    /**
     * Adds a given language to a Game version
     *
     * @param $gameVersionId int the id of the game version
     * @param $langId int the id of the language
     */
    public function addGameVersionLanguage($gameVersionId, $langId) {
        $gameVersionLanguage = new GameVersionLanguage();
        $gameVersionLanguage->lang_id = $langId;
        $gameVersionLanguage->game_version_id = $gameVersionId;
        $this->gameVersionLanguageStorage->storeGameVersionLanguage($gameVersionLanguage);
    }
}
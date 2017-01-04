<?php
/**
 * Created by IntelliJ IDEA.
 * User: pisaris
 * Date: 3/1/2017
 * Time: 10:46 πμ
 */

namespace App\BusinessLogicLayer\managers;


use App\Models\GameVersion;
use App\StorageLayer\GameVersionStorage;
use Illuminate\Support\Facades\Auth;

class GameVersionManager {

    private $gameVersionStorage;

    public function __construct() {
        // initialize $userStorage
        $this->gameVersionStorage = new GameVersionStorage();
    }

    public function createGameVersion(array $gameVersionFields) {
        $gameVersion = new GameVersion;
        $gameVersion = $this->assignValuesToGameVersion($gameVersion, $gameVersionFields);

        return $this->gameVersionStorage->storeGameVersion($gameVersion);
    }

    public function getGameVersions() {
        $user = Auth::user();
        if($user != null)
            if($user->isAdmin()) {
                return $this->gameVersionStorage->getAllGameVersions();
            }
        return $this->gameVersionStorage->getPublishedGameVersions();
    }

    /**
     * Gets a Version
     *
     * @param $id. the id of game version
     */
    public function getGameVersion($id) {
        return $this->gameVersionStorage->getGameVersionById($id);
    }

    /**
     * Edits the game version identified by id
     *
     * @param $id . game version id
     * @param $gameVersionFields . the new values
     * @return GameVersion the edited object
     */
    public function editGameVersion($id, $gameVersionFields) {
        $gameVersionToBeEdited = $this->getGameVersion($id);

        $gameVersionToBeEdited = $this->assignValuesToGameVersion($gameVersionToBeEdited, $gameVersionFields);

        return $this->gameVersionStorage->storeGameVersion($gameVersionToBeEdited);
    }

    private function assignValuesToGameVersion(GameVersion $gameVersion, $gameVersionFields) {
        $gameVersion->name = $gameVersionFields['name'];
        $gameVersion->description = $gameVersionFields['description'];
        $gameVersion->lang_id = $gameVersionFields['lang_id'];
        if(isset($gameVersionFields['creator_id']) && $gameVersionFields['creator_id'] != null)
            $gameVersion->creator_id = $gameVersionFields['creator_id'];
        if($gameVersionFields['cover_img_id'] != null)
            $gameVersion->cover_img_id = $gameVersionFields['cover_img_id'];
        return $gameVersion;
    }
}
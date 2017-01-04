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
use Illuminate\Database\Eloquent\Collection;
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

        //if not logged in user, get only the published versions
        if($user != null) {
            if ($user->isAdmin()) {
                //if admin, get all game versions
                $gameVersionsToBeReturned = $this->gameVersionStorage->getAllGameVersions();
            } else {
                //if regular user, merge the published game versions with the game versions created by the user
                $publishedGameVersions = $this->gameVersionStorage->getGameVersionsByPublishedState(true);
                $gameVersionsCreatedByUser = $this->gameVersionStorage->getGameVersionsByPublishedStateByUser(false, $user->id);

                $gameVersionsToBeReturned = $gameVersionsCreatedByUser->merge($publishedGameVersions);
            }
        } else {
            $gameVersionsToBeReturned = $this->gameVersionStorage->getGameVersionsByPublishedState(true);
        }

        return $gameVersionsToBeReturned;

    }

    /**
     * Gets a Version
     *
     * @param $id . the id of game version
     * @return GameVersion desired {@see GameVersion} object, or null if the user has no access to this object
     */
    public function getGameVersionForEdit($id) {
        $user = Auth::user();
        if($user != null) {
            if ($user->isAdmin()) {
                $gameVersion = $this->gameVersionStorage->getGameVersionById($id);
            } else {
                $gameVersion = $this->gameVersionStorage->getGameVersionByIdCreatedByUser($id, $user->id);
            }
        } else {
            $gameVersion = null;
        }

        return $gameVersion;
    }

    /**
     * Edits the game version identified by id
     *
     * @param $id . game version id
     * @param $gameVersionFields . the new values
     * @return GameVersion the edited object
     */
    public function editGameVersion($id, $gameVersionFields) {
        $gameVersionToBeEdited = $this->getGameVersionForEdit($id);

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


    /**
     * @param $gameVersionId. The id of the game version to be deleted
     * @return bool. True if the game version was deleted successfully, false if the user has no access
     */
    public function deleteGameVersion($gameVersionId) {
        $gameVersion = $this->getGameVersionForEdit($gameVersionId);
        if($gameVersion == null)
            return false;
        $this->gameVersionStorage->deleteGameVersion($gameVersion);
        return true;
    }
}
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
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameVersionManager {

    private $gameVersionStorage;

    public function __construct() {
        // initialize $userStorage
        $this->gameVersionStorage = new GameVersionStorage();
    }

    public function saveGameVersion($gameVersionId, array $gameVersionFields, Request $request) {
        //TODO: add try-catch
        //upload the cover image
        if($request->hasFile('cover_img')) {
            $gameVersionFields['cover_img_id'] = $this->processFile($request);
            if ($gameVersionFields['cover_img_id'] == null)
                return null;
        } else {
            $gameVersionFields['cover_img_id'] = null;
        }

        if($gameVersionId == null) {
            $gameVersionFields['creator_id'] = Auth::user()->id;
            $gameVersion = new GameVersion;
            $gameVersion = $this->assignValuesToGameVersion($gameVersion, $gameVersionFields);
        } else {

            $gameVersion = $this->getGameVersionForEdit($gameVersionId);

            $gameVersion = $this->assignValuesToGameVersion($gameVersion, $gameVersionFields);
        }

        return $this->gameVersionStorage->storeGameVersion($gameVersion);
    }

    private function processFile(Request $request) {
        $imgManager = new ImgManager();
        return $imgManager->uploadGameVersionCoverImg($request->file('cover_img'));
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

        foreach ($gameVersionsToBeReturned as $gameVersion) {
            $gameVersion->accessed_by_user = $this->isGameVersionAccessedByUser($gameVersion, $user);
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
        $gameVersion = $this->gameVersionStorage->getGameVersionById($id);

        //if the game Version exists, check if the user has access
        if($gameVersion != null) {
            if ($this->isGameVersionAccessedByUser($gameVersion, $user))
                return $gameVersion;
        }

        return null;
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

    /**
     * Checks if a game Version object is accessed by a user
     * (If user is admin or has created it, then they should have access, otherwise they should not)
     *
     * @param $gameVersion GameVersion
     * @param $user User
     * @return bool user access
     */
    private function isGameVersionAccessedByUser($gameVersion, $user) {
        if($user == null)
            return false;
        if($user->isAdmin())
            return true;
        if($gameVersion->creator->id == $user->id)
            return true;
        return false;
    }
}
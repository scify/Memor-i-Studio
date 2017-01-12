<?php
/**
 * Created by IntelliJ IDEA.
 * User: pisaris
 * Date: 3/1/2017
 * Time: 10:46 πμ
 */

namespace App\BusinessLogicLayer\managers;


use App\Models\GameFlavor;
use App\StorageLayer\GameFlavorStorage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameFlavorManager {

    private $gameVersionStorage;

    public function __construct() {
        // initialize $userStorage
        $this->gameVersionStorage = new GameFlavorStorage();
    }

    public function saveGameFlavor($gameVersionId, array $gameVersionFields, Request $request) {

        if($gameVersionId == null) {
            $gameVersionFields['creator_id'] = Auth::user()->id;
            $gameVersion = new GameFlavor;
            $gameVersion = $this->assignValuesToGameFlavor($gameVersion, $gameVersionFields);
        } else {

            $gameVersion = $this->getGameFlavorForEdit($gameVersionId);

            $gameVersion = $this->assignValuesToGameFlavor($gameVersion, $gameVersionFields);
        }
        //TODO: add try-catch
        //upload the cover image
        if($request->hasFile('cover_img')) {
            $gameVersion['cover_img_id'] = $this->processFile($gameVersion->id, $request);
            if ($gameVersion['cover_img_id'] == null)
                return null;
        }

        return $this->gameVersionStorage->storeGameFlavor($gameVersion);
    }

    private function processFile($gameVersionId, Request $request) {
        $imgManager = new ImgManager();
        return $imgManager->uploadGameFlavorCoverImg($gameVersionId, $request->file('cover_img'));
    }

    public function getGameFlavors() {
        $user = Auth::user();

        //if not logged in user, get only the published versions
        if($user != null) {
            if ($user->isAdmin()) {
                //if admin, get all game versions
                $gameVersionsToBeReturned = $this->gameVersionStorage->getAllGameFlavors();
            } else {
                //if regular user, merge the published game versions with the game versions created by the user
                $publishedGameFlavors = $this->gameVersionStorage->getGameFlavorsByPublishedState(true);
                $gameVersionsCreatedByUser = $this->gameVersionStorage->getGameFlavorsByPublishedStateByUser(false, $user->id);

                $gameVersionsToBeReturned = $gameVersionsCreatedByUser->merge($publishedGameFlavors);
            }
        } else {
            $gameVersionsToBeReturned = $this->gameVersionStorage->getGameFlavorsByPublishedState(true);
        }

        foreach ($gameVersionsToBeReturned as $gameVersion) {
            $gameVersion->accessed_by_user = $this->isGameFlavorAccessedByUser($gameVersion, $user);
        }

        return $gameVersionsToBeReturned;

    }



    /**
     * Gets a Flavor if the user has access rights
     *
     * @param $id . the id of game version
     * @return GameFlavor desired {@see GameFlavor} object, or null if the user has no access to this object
     */
    public function getGameFlavorForEdit($id) {
        $user = Auth::user();
        $gameFlavor = $this->gameVersionStorage->getGameFlavorById($id);

        //if the game Version exists, check if the user has access
        if($gameFlavor != null) {
            if ($this->isGameFlavorAccessedByUser($gameFlavor, $user))
                return $gameFlavor;
        }

        return null;
    }

    /**
     * Gets a game flavor
     *
     * @param $id . the id of game version
     * @return GameFlavor desired {@see GameFlavor} object
     */
    public function getGameFlavor($id) {
        $user = Auth::user();
        $gameFlavor = $this->gameVersionStorage->getGameFlavorById($id);

        //if the game Version exists, check if the user has access
        if($gameFlavor != null) {
            $gameFlavor->accessed_by_user = $this->isGameFlavorAccessedByUser($gameFlavor, $user);
        }

        return $gameFlavor;
    }


    private function assignValuesToGameFlavor(GameFlavor $gameVersion, $gameVersionFields) {

        $gameVersion->name = $gameVersionFields['name'];
        $gameVersion->description = $gameVersionFields['description'];
        $gameVersion->lang_id = $gameVersionFields['lang_id'];

        if(isset($gameVersionFields['creator_id']) && $gameVersionFields['creator_id'] != null)
            $gameVersion->creator_id = $gameVersionFields['creator_id'];
//        if($gameVersionFields['cover_img_id'] != null)
//            $gameVersion->cover_img_id = $gameVersionFields['cover_img_id'];

        return $gameVersion;
    }



    /**
     * @param $gameVersionId. The id of the game version to be deleted
     * @return bool. True if the game version was deleted successfully, false if the user has no access
     */
    public function deleteGameFlavor($gameVersionId) {
        $gameVersion = $this->getGameFlavorForEdit($gameVersionId);
        if($gameVersion == null)
            return false;
        $this->gameVersionStorage->deleteGameFlavor($gameVersion);
        return true;
    }

    /**
     * Checks if a game Version object is accessed by a user
     * (If user is admin or has created it, then they should have access, otherwise they should not)
     *
     * @param $gameVersion GameFlavor
     * @param $user User
     * @return bool user access
     */
    private function isGameFlavorAccessedByUser($gameVersion, $user) {
        if($user == null)
            return false;
        if($user->isAdmin())
            return true;
        if($gameVersion->creator->id == $user->id)
            return true;
        return false;
    }

    public function toggleGameFlavorState($gameVersionId) {
        $gameVersion = $this->getGameFlavorForEdit($gameVersionId);
        if($gameVersion == null)
            return false;
        $gameVersion = $this->toggleGameFlavorPublishedAttribute($gameVersion);
        if($this->gameVersionStorage->storeGameFlavor($gameVersion) != null) {
            return true;
        }
        return false;
    }


    private function toggleGameFlavorPublishedAttribute($gameVersion) {
        $gameVersion->published = !$gameVersion->published;
        return $gameVersion;
    }
}
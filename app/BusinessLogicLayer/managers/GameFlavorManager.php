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
use App\StorageLayer\ResourceFileStorage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Chumper\Zipper\Zipper;
use Illuminate\Support\Facades\Storage;

class GameFlavorManager {

    private $gameFlavorStorage;

    public function __construct() {
        // initialize $userStorage
        $this->gameFlavorStorage = new GameFlavorStorage();
    }

    public function saveGameFlavor($gameFlavorId, array $inputFields, Request $request) {

        if($gameFlavorId == null) {
            //create new instance
            $inputFields['creator_id'] = Auth::user()->id;
            $gameFlavor = new GameFlavor;
            $gameFlavor = $this->assignValuesToGameFlavor($gameFlavor, $inputFields);
            $gameFlavor->game_version_id = $inputFields['game_version_id'];
            $gameFlavor->creator_id = $inputFields['creator_id'];
        } else {
            //edit existing
            $gameFlavor = $this->getGameFlavorForEdit($gameFlavorId);

            $gameFlavor = $this->assignValuesToGameFlavor($gameFlavor, $inputFields);
        }
        //upload the cover image
        //TODO: this should be done in resources page
//        if($request->hasFile('cover_img')) {
//            $gameFlavor['cover_img_id'] = $this->uploadCoverImageFile($gameFlavor->id, $request);
//            if ($gameFlavor['cover_img_id'] == null)
//                return null;
//        }

        return $this->gameFlavorStorage->storeGameFlavor($gameFlavor);
    }

    public function getResourcesForGameFlavor($gameFlavor) {
        $resourceCategoryManager = new ResourceCategoryManager();
        $resourceFileStorage = new ResourceFileStorage();
        $gameVersionResourceCategories = $resourceCategoryManager->getResourceCategoriesForGameVersionForLanguage($gameFlavor->game_version_id, $gameFlavor->lang_id);
        foreach ($gameVersionResourceCategories as $category) {

            $currCatResources = $category->resources;
            foreach ($currCatResources as $resource) {
                $fileForResource = $resourceFileStorage->getPathForGameFlavorResource($resource->id, $gameFlavor->id);
                if($fileForResource != null) {
                    $resource->file_path = $fileForResource->file_path;
                } else {
                    $resource->file_path = null;
                }
            }
        }
        return $gameVersionResourceCategories;
    }

    private function uploadCoverImageFile($gameVersionId, Request $request) {
        $imgManager = new ImgManager();
        return $imgManager->uploadGameFlavorCoverImg($gameVersionId, $request->file('cover_img'));
    }

    public function getGameFlavors() {
        $user = Auth::user();

        //if not logged in user, get only the published versions
        if($user != null) {
            if ($user->isAdmin()) {
                //if admin, get all game versions
                $gameFlavorsToBeReturned = $this->gameFlavorStorage->getAllGameFlavors();
            } else {
                //if regular user, merge the published game versions with the game versions created by the user
                $publishedGameFlavors = $this->gameFlavorStorage->getGameFlavorsByPublishedState(true);
                $gameFlavorsCreatedByUser = $this->gameFlavorStorage->getGameFlavorsByPublishedStateByUser(false, $user->id);

                $gameFlavorsToBeReturned = $gameFlavorsCreatedByUser->merge($publishedGameFlavors);
            }
        } else {
            $gameFlavorsToBeReturned = $this->gameFlavorStorage->getGameFlavorsByPublishedState(true);
        }

        foreach ($gameFlavorsToBeReturned as $gameVersion) {
            $gameVersion->accessed_by_user = $this->isGameFlavorAccessedByUser($gameVersion, $user);
        }

        return $gameFlavorsToBeReturned;

    }



    /**
     * Gets a Flavor if the user has access rights
     *
     * @param $id . the id of game version
     * @return GameFlavor desired {@see GameFlavor} object, or null if the user has no access to this object
     */
    public function getGameFlavorForEdit($id) {
        $user = Auth::user();
        $gameFlavor = $this->gameFlavorStorage->getGameFlavorById($id);

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
        $gameFlavor = $this->gameFlavorStorage->getGameFlavorById($id);

        //if the game Version exists, check if the user has access
        if($gameFlavor != null) {
            $gameFlavor->accessed_by_user = $this->isGameFlavorAccessedByUser($gameFlavor, $user);
        }

        return $gameFlavor;
    }


    private function assignValuesToGameFlavor(GameFlavor $gameFlavor, $gameFlavorFields) {

        $gameFlavor->name = $gameFlavorFields['name'];
        $gameFlavor->description = $gameFlavorFields['description'];
        $gameFlavor->lang_id = $gameFlavorFields['lang_id'];
        $gameFlavor->interface_lang_id = $gameFlavorFields['interface_lang_id'];

        return $gameFlavor;
    }



    /**
     * @param $gameFlavorId. The id of the game version to be deleted
     * @return bool. True if the game version was deleted successfully, false if the user has no access
     */
    public function deleteGameFlavor($gameFlavorId) {
        $gameFlavor = $this->getGameFlavorForEdit($gameFlavorId);
        if($gameFlavor == null)
            return false;
        $this->gameFlavorStorage->deleteGameFlavor($gameFlavor);
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

    /**
     * Toggles the @see GameFlavor instance's published attribute
     *
     * @param $gameVersionId int the id of the @see GameFlavor to be updated
     * @return bool if the update process was successful or not
     */
    public function toggleGameFlavorState($gameVersionId) {
        $gameFlavor = $this->getGameFlavorForEdit($gameVersionId);
        if($gameFlavor == null)
            return false;
        $gameFlavor->published = !$gameFlavor->published;
        if($this->gameFlavorStorage->storeGameFlavor($gameFlavor) != null) {
            return true;
        }
        return false;
    }

    /**
     * Prepares the JSON file describing the equivalence sets
     *
     * @param $gameFlavorId int the id of the @see GameFlavor
     * @return string
     */
    public function createEquivalenceSetsJSONFile($gameFlavorId) {
        $equivalenceSetManager = new EquivalenceSetManager();
        $equivalenceSets = $equivalenceSetManager->getEquivalenceSetsForGameFlavor($gameFlavorId);
        //dd($equivalenceSets);
        $equivalence_card_sets = array();
        $equivalence_card_sets['equivalence_card_sets'] = array();
        foreach ($equivalenceSets as $equivalenceSet) {
            $cards = array();

            foreach ($equivalenceSet->cards as $card) {
                $current_card = array();
                $current_card['label'] = $card->label;
                $current_card['category'] = $card->category;
                $current_card['unique'] = $card->unique;
                $current_card['sounds'] = array();
                $current_card['images'] = array();
                $current_card['description_sound'] = "";
                $current_card['equivalenceCardSetHashCode'] = "";
                array_push($current_card['sounds'], $card->sound->file_path);
                if($card->image != null)
                    array_push($current_card['images'], $card->image->file_path);
                if($card->secondImage != null)
                    array_push($current_card['images'], $card->secondImage->file_path);
                array_push($cards, $current_card);
            }
            array_push($equivalence_card_sets['equivalence_card_sets'], $cards);
        }
        $filePath = storage_path() . '/app/packs/' . $gameFlavorId . '/json_DB/equivalence_card_sets.json';
        if(!File::exists($filePath)) {
            File::delete($filePath);
        }

        Storage::put('packs/' . $gameFlavorId . '/json_DB/equivalence_card_sets.json', json_encode($equivalence_card_sets));

        return json_encode($equivalence_card_sets);
    }

    /**
     * Zips a directory containing Game flavor data (images, sounds, etc) into a .zip file
     *
     * @param $gameFlavorId int the id of the @see GameFlavor
     */
    public function zipGameFlavor($gameFlavorId) {
        $packDir = storage_path() . '/app/packs/' . $gameFlavorId;
        $zipper = new Zipper();
        $zipFile = storage_path() . '/app/zips/' . 'memori_data_' . $gameFlavorId . '.zip';
        if(File::exists($zipFile)) {
            File::delete($zipFile);
        }
        $zipper->make($zipFile)
            ->add($packDir);
    }
}
<?php

namespace App\BusinessLogicLayer\managers;

use App\Models\GameFlavor;
use App\StorageLayer\GameFlavorStorage;
use App\Models\User;
use DOMDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Chumper\Zipper\Zipper;

include_once 'functions.php';

/**
 * Contains the functionality, methods and handling logic for the @see GameFlavor class
 *
 * Class GameFlavorManager
 * @package App\BusinessLogicLayer\managers
 */
class GameFlavorManager {

    private $gameFlavorStorage;

    public function __construct() {
        // initialize $userStorage
        $this->gameFlavorStorage = new GameFlavorStorage();
    }

    /**
     * @param $gameFlavorId int id of the game flavor
     * @param array $inputFields contain the game flavor parameters
     * @param Request $request the request object
     * @return GameFlavor the newly created instance
     */
    public function saveGameFlavor($gameFlavorId, array $inputFields, Request $request) {
        $imgManager = new ImgManager();
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
        $newGameFlavor = $this->gameFlavorStorage->storeGameFlavor($gameFlavor);
        if(isset($inputFields['cover_img'])) {
            $gameFlavor->cover_img_id = $imgManager->uploadGameFlavorCoverImg($newGameFlavor->id, $inputFields['cover_img']);
        }
        $gameFlavor->save();

        return $newGameFlavor;
    }

    public function getResourcesForGameFlavor($gameFlavor) {
        $resourceCategoryManager = new ResourceCategoryManager();
        $gameVersionResourceCategories = $resourceCategoryManager->getResourceCategoriesForGameVersionForLanguage($gameFlavor->game_version_id, $gameFlavor->lang_id);
        foreach ($gameVersionResourceCategories as $category) {

            $currCatResources = $category->resources;
            foreach ($currCatResources as $resource) {
                if($resource->file != null) {
                    $resource->file_path = $resource->file->file_path;
                } else {
                    $resource->file_path = null;
                }
            }
        }
        return $gameVersionResourceCategories;
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
        //TODO: this should be done elsewhere
//        $resourceManager = new ResourceManager();
//        $resourceManager->createStaticResourcesMapFile($id);

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
     * Zips a directory containing Game flavor data (images, sounds, etc) into a .zip file
     *
     * @param $gameFlavorId int the id of the @see GameFlavor
     */
    public function zipGameFlavorDataPack($gameFlavorId) {
        $packDir = storage_path() . '/app/data_packs/additional_pack_' . $gameFlavorId;
        $zipper = new Zipper();
        $zipFile = storage_path() . '/app/data_packs/jnlp/' . $gameFlavorId . '/memori_data_flavor_' . $gameFlavorId . '.jar';
        if(File::exists($zipFile)) {
            File::delete($zipFile);
        }
        $zipper->make($zipFile)
            ->add($packDir);
    }

    public function getGameFlavorZipFile($gameFlavorId) {
        return storage_path('app/data_packs/jnlp/' . $gameFlavorId . '/memori_data_flavor_' . $gameFlavorId . '.jar');
    }

    public function packageFlavor($gameFlavorId) {
        $resourceManager = new ResourceManager();
        $equivalenceSetManager = new EquivalenceSetManager();
        //create resources map file
        $resourceManager->createStaticResourcesMapFile($gameFlavorId);
        $resourceManager->createAdditionalPropertiesFile($gameFlavorId);
        //create card .json file (for equivalent sets)
        $equivalenceSetManager->createEquivalenceSetsJSONFile($gameFlavorId);
        //compress the data pack directory into a .jar file
        $this->zipGameFlavorDataPack($gameFlavorId);

        //get the path
        $packagePath = $this->getGameFlavorZipFile($gameFlavorId);
        $filePathToStore = storage_path() . '/app/data_packs/jnlp/'. $gameFlavorId . '/memori_data_signed.jar ';

        //we need to sign the created jar file
        $output = $this->signDataPackJarFile($filePathToStore, $packagePath);

        //copy the public jnlp file into the game flavor jnlp directory
        $this->copyGameVersionJarFileToJnlpDir($gameFlavorId);
        //add the jnlp file into the public directory (or storage directory)
        $this->copyAndUpdateJnlpFileToDir($gameFlavorId);
        return;
    }

    private function signDataPackJarFile($filePathToStore, $packagePath) {
        $keyStorePass = config('app.KEYSTORE_PASS');
        $old_path = getcwd();
        chdir(public_path());
        $command = './sign_data_pack.sh ' . $filePathToStore . ' ' . $packagePath . ' ' . $keyStorePass;
        //dd($command);
        $output = shell_exec($command);
        chdir($old_path);
        return $output;
    }

    private function copyGameVersionJarFileToJnlpDir($gameFlavorId) {
        $gameVersionManager = new GameVersionManager();
        $gameFlavor = $this->getGameFlavor($gameFlavorId);
        $destinationPath = storage_path() . '/app/data_packs/jnlp/'. $gameFlavorId . '/memori.jar';
        if ( ! File::copy($gameVersionManager->getGameVersionZipFile($gameFlavor->game_version_id), $destinationPath)) {
            throw new \Exception("Couldn't copy version jar file");
        }
    }

    private function copyAndUpdateJnlpFileToDir($gameFlavorId) {
        $pathToJnlpFile = storage_path() . '/app/data_packs/jnlp/'. $gameFlavorId . '/main_'. $gameFlavorId . '.jnlp';
        if(File::exists($pathToJnlpFile)) {
            File::delete($pathToJnlpFile);
        }
        if ( ! File::copy(public_path() . '/main.jnlp', $pathToJnlpFile)) {
            throw new \Exception("Couldn't copy jnlp file");
        }
        $this->updateJnlpFile($gameFlavorId, $pathToJnlpFile);
    }

    public function getJnlpFileForGameFlavor($gameFlavorId) {
        return storage_path() . '/app/data_packs/jnlp/'. $gameFlavorId . '/main_'. $gameFlavorId . '.jnlp';
    }

    private function updateJnlpFile($gameFlavorId, $pathToJnlpFile) {
        $dom = new DOMDocument();
        $dom->load($pathToJnlpFile);
        $root = $dom->documentElement;

        if ($root != null) {
            $root->setAttribute('href', 'resolveData/data_packs/jnlp/' . $gameFlavorId . '/main_' . $gameFlavorId . '.jnlp');
        }

        $jarElements= $root->getElementsByTagName('jar');
        if ($jarElements->length >= 1) {
            $elementJarMain = $jarElements->item(0);
            $elementJarMain->setAttribute('href', 'resolveData/data_packs/jnlp/' . $gameFlavorId . '/memori.jar');
            //$elementJarMain->setAttribute('version', milliseconds());
            $elementJarData = $jarElements->item(1);
            $elementJarData->setAttribute('href', 'resolveData/data_packs/jnlp/' . $gameFlavorId . '/memori_data_signed.jar');
            //$elementJarData->setAttribute('version', milliseconds());
        }
        $dom->save($pathToJnlpFile);
    }
}
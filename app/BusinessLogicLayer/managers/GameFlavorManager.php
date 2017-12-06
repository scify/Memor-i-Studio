<?php

namespace App\BusinessLogicLayer\managers;

use App\BusinessLogicLayer\WindowsBuilder;
use App\Models\GameFlavor;
use App\Models\ResourceFile;
use App\StorageLayer\GameFlavorStorage;
use App\Models\User;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Chumper\Zipper\Zipper;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Exception;

include_once 'functions.php';

/**
 * Contains the functionality, methods and handling logic for the @see GameFlavor class
 *
 * Class GameFlavorManager
 * @package App\BusinessLogicLayer\managers
 */
class GameFlavorManager {

    private $gameFlavorStorage;
    //the string that will be used to name the final jnlp file
    //private $JNLP_FILE_PREFIX = "game";
    private $fileManager;

    public function __construct() {
        $this->gameFlavorStorage = new GameFlavorStorage();
        $this->fileManager = new FileManager();
    }

    public function getJarFilePathForGameFlavor($gameFlavorId) {
        return storage_path() . '/app/data_packs/additional_pack_'. $gameFlavorId . '/memori.jar';
    }


    //TODO: refactor separate into create and edit
    /**
     * @param $gameFlavorId int id of the game flavor
     * @param array $inputFields contain the game flavor parameters
     * @return GameFlavor the newly created instance
     * @internal param Request $request the request object
     */
    public function createGameFlavor($gameFlavorId, array $inputFields) {

        if ($gameFlavorId == null) {
            //create new instance
            $inputFields['creator_id'] = Auth::user()->id;
            $gameFlavor = new GameFlavor;
            $gameFlavor = $this->assignValuesToGameFlavor($gameFlavor, $inputFields);
            $gameFlavor->game_version_id = $inputFields['game_version_id'];
            $gameFlavor->creator_id = $inputFields['creator_id'];
        } else {
            //edit existing
            $gameFlavor = $this->getGameFlavor($gameFlavorId);

            $gameFlavor = $this->assignValuesToGameFlavor($gameFlavor, $inputFields);
            $this->markGameFlavorAsSubmittedForApproval($gameFlavor->id);
        }
        DB::transaction(function() use($gameFlavor, $inputFields) {
            $imgManager = new ImgManager();
            if(!$gameFlavor->game_identifier)
                $gameFlavor->game_identifier = $this->createIdentifierForGameFlavor($gameFlavor);
            $gameFlavor = $this->gameFlavorStorage->storeGameFlavor($gameFlavor);

            if (isset($inputFields['cover_img'])) {
                $gameFlavor->cover_img_id = $imgManager->uploadGameFlavorCoverImg($gameFlavor, $inputFields['cover_img']);
                $resourceManager = new ResourceManager();
                $gameFlavorImgCoverFile = $resourceManager->getFileForResourceForGameFlavor($gameFlavor->coverImg, $gameFlavor->id);
                $this->convertGameFlavorCoverImgToIcon($gameFlavorImgCoverFile);
            }
            $gameFlavor->save();
        });
        return $gameFlavor;

    }

    public function assignGameFlavorToGameVersion($gameFlavorId, $gameVersionId) {
        $gameFlavor = $this->getGameFlavor($gameFlavorId);
        $gameVersionManager = new GameVersionManager();
        $gameVersion = $gameVersionManager->getGameVersion($gameVersionId);

        if(!$gameFlavor || !$gameVersion)
            throw new \Exception("ERROR game flavor id: " . $gameFlavorId . " or game version id: " . $gameVersionId . " do not exist");
        $gameFlavor->game_version_id = $gameVersion->id;
        $gameFlavor->save();
        return $gameFlavor;
    }

    private function createIdentifierForGameFlavor(GameFlavor $gameFlavor) {
        return greeklish($gameFlavor->name);
    }

    public function getResourceCategoriesForGameFlavor($gameFlavor, $langId) {
        $resourceCategoryManager = new ResourceCategoryManager();
        $resourceManager = new ResourceManager();
        $gameVersionResourceCategories = $resourceCategoryManager->getResourceCategoriesForGameVersionForLanguage($gameFlavor->game_version_id, $langId);
        foreach ($gameVersionResourceCategories as $category) {
            $currCatResources = $category->resources;
            foreach ($currCatResources as $resource) {
                $resourceFile = $resourceManager->getFileForResourceForGameFlavor($resource, $gameFlavor->id);
                if($resourceFile != null) {
                    $resource->file_path = $resourceFile->file_path;
                } else {
                    if(File::exists(storage_path('app/game_versions/data/' . $gameFlavor->game_version_id . '/' . $resource->name))) {
                        $resource->file_path = 'game_versions/data/' . $gameFlavor->game_version_id . '/' . $resource->name;
                    } else {
                        $resource->file_path = null;
                    }
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

        foreach ($gameFlavorsToBeReturned as $gameFlavor) {
            $gameFlavor->cover_img_file_path = $this->getGameFlavorCoverImgFilePath($gameFlavor);
            $gameFlavor->accessed_by_user = $this->isGameFlavorAccessedByUser($gameFlavor, $user);
        }
        return $gameFlavorsToBeReturned;
    }

    private function getGameFlavorCoverImgFilePath(GameFlavor $gameFlavor) {
        $resource = $gameFlavor->coverImg;
        if($resource != null) {
            $resourceManager = new ResourceManager();
            $resourceFile = $resourceManager->getFileForResourceForGameFlavor($resource, $gameFlavor->id);
            if($resourceFile != null)
                return $resourceFile->file_path;
        }
        return null;
    }


    /**
     * Gets a Flavor if the user has access rights
     *
     * @param $id . the id of game version
     * @return GameFlavor desired {@see GameFlavor} object, or null if the user has no access to this object
     * @throws \Exception if a game flavor is not found
     */
    public function getGameFlavor($id) {
        $user = Auth::user();
        $gameFlavor = $this->gameFlavorStorage->getGameFlavorById($id);

        //if the game Version exists, check if the user has access
        if($gameFlavor != null) {
            if ($this->isGameFlavorAccessedByUser($gameFlavor, $user))
                return $gameFlavor;
        } else {
            throw new \Exception("Game flavor not found. Id queried: " . $id);
        }

        return null;
    }

    public function getGameFlavorByGameIdentifier($gameFlavorIdentifier) {
        $gameFlavor = $this->gameFlavorStorage->getGameFlavorByGameIdentifier($gameFlavorIdentifier);
        if(!$gameFlavor) {
                throw new \Exception("Game flavor not found. Identifier queried: " . $gameFlavorIdentifier);
        }

        return $gameFlavor;
    }

    /**
     * Gets a game flavor
     *
     * @param $id . the id of game version
     * @return GameFlavor desired <a href='psi_element://GameFlavor'>GameFlavor</a> object
     * object
     * @throws Exception if no game flavor found by the given id
     */
    public function getGameFlavorViewModel($id) {
        $user = Auth::user();
        $gameFlavor = $this->gameFlavorStorage->getGameFlavorById($id);
        //if the game Version exists, check if the user has access
        if($gameFlavor != null) {
            $gameFlavor->accessed_by_user = $this->isGameFlavorAccessedByUser($gameFlavor, $user);
            $gameFlavor->cover_img_file_path = $this->getGameFlavorCoverImgFilePath($gameFlavor);
        } else {
            throw new Exception("Game flavor not found");
        }

        return $gameFlavor;
    }


    private function assignValuesToGameFlavor(GameFlavor $gameFlavor, $gameFlavorFields) {
        $gameFlavor->name = $gameFlavorFields['name'];
        $gameFlavor->description = $gameFlavorFields['description'];
        $gameFlavor->lang_id = $gameFlavorFields['lang_id'];
        $gameVersionLanguageManager = new GameVersionLanguageManager();
        $gameFlavor->interface_lang_id = $gameVersionLanguageManager->getFirstLanguageAvailableForGameVersion($gameFlavorFields['game_version_id'])->lang_id;
        $gameFlavor->copyright_link = $gameFlavorFields['copyright_link'];
        if(isset($gameFlavorFields['allow_clone']))
            $gameFlavor->allow_clone = true;
        else
            $gameFlavor->allow_clone = false;

        return $gameFlavor;
    }

    /**
     * @param $gameFlavorId. The id of the game version to be deleted
     * @return bool. True if the game version was deleted successfully, false if the user has no access
     */
    public function deleteGameFlavor($gameFlavorId) {
        $gameFlavor = $this->getGameFlavor($gameFlavorId);
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
     * @param $gameFlavorId int the id of the @see GameFlavor to be updated
     * @return bool if the update process was successful or not
     */
    public function toggleGameFlavorPublishedState($gameFlavorId) {
        $gameFlavor = $this->getGameFlavor($gameFlavorId);
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
        $zipFile = storage_path() . '/app/data_packs/additional_pack_' . $gameFlavorId . '/memori_data_flavor_' . $gameFlavorId . '.jar';
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
        //compress the data pack directory into a temporary .jar file (it will be deleted later, after we sign it)
        //$this->zipGameFlavorDataPack($gameFlavorId);

        //get a timestamp as a random string in order to name the generated jar files appropriately
        //$randomSuffix = milliseconds();
        //get the path
        //$packagePath = $this->getGameFlavorZipFile($gameFlavorId);
        //$filePathToStore = storage_path() . '/app/data_packs/jnlp/'. $gameFlavorId . '/memori_data_signed-' . $randomSuffix . '.jar ';

        //we need to sign the created jar file
        //$output = $this->signDataPackJarFile($filePathToStore, $packagePath);

        //delete not-signed jar file
        //$this->deleteTemporaryFlavorPackZipFile($gameFlavorId);
        //copy the jar file for the current game version into the jnlp directory and name it appropriately

        try {
            $this->copyGameVersionJarFileToDataPackDir($gameFlavorId);
            $this->addDataPackIntoJar($gameFlavorId);
        } catch (\Exception $e) {
            throw $e;
        }
        //copy the public jnlp file into the game flavor jnlp directory
        //$this->copyAndUpdateJnlpFileToDir($gameFlavorId, $randomSuffix);
        $windowsBuilder = new WindowsBuilder();

        $windowsBuilder->buildGameFlavorForWindows($this->getGameFlavorViewModel($gameFlavorId), $this->getJarFilePathForGameFlavor($gameFlavorId));

        return;
    }

    /**
     * @param $gameFlavorId int the id of the @see GameFlavor
     * @throws \Exception if the .jar file cannot be copied to the destination path
     */
    private function copyGameVersionJarFileToDataPackDir($gameFlavorId) {
        $gameVersionManager = new GameVersionManager();
        $gameFlavor = $this->getGameFlavorViewModel($gameFlavorId);
        $sourceFile = $gameVersionManager->getGameVersionJarFile($gameFlavor->game_version_id);
        $destinationFile = storage_path() . '/app/data_packs/additional_pack_'. $gameFlavorId . '/memori.jar';
        $this->fileManager->copyFileToDestinationAndReplace($sourceFile, $destinationFile);
    }

    private function addDataPackIntoJar($gameFlavorId) {
        $old_path = getcwd();
        chdir(storage_path() . '/app/data_packs/additional_pack_'. $gameFlavorId);
        // if the game version .jar is already a game flavor build, there is already a project_additional.properties
        // inside the .jar file. We need to get this file and append it's contents to the project_additional.properties
        // file for this game flavor.
        // then we delete the project_additional.properties file and we add the newly created one
        $existingAdditionalPropertiesFileContents = shell_exec('unzip -p memori.jar project_additional.properties');
        if($existingAdditionalPropertiesFileContents) {
            $this->replaceAdditionalPropertiesFileForGameFlavor($gameFlavorId, $existingAdditionalPropertiesFileContents);
        }
        $command = 'zip -ur memori.jar project_additional.properties data/*';
        $output = shell_exec($command);
        chdir($old_path);
        return $output;
    }

    private function replaceAdditionalPropertiesFileForGameFlavor($gameFlavorId, $existingAdditionalPropertiesFileContents) {
        $pathToPropsFile = 'data_packs/additional_pack_' . $gameFlavorId . '/' . 'project_additional.properties';
        foreach(preg_split("/((\r?\n)|(\r\n?))/", $existingAdditionalPropertiesFileContents) as $line){
            if($line) {
                $arr = explode("=", $line, 2);
                $propertyName = $arr[0];
                if(!$this->propertyExistsInPropertiesFile($propertyName, $pathToPropsFile)) {
                    Storage::append($pathToPropsFile, $line);
                }
            }
        }
    }

    private function propertyExistsInPropertiesFile($property, $pathToPropsFile) {
        $contents = Storage::get($pathToPropsFile);
        foreach(preg_split("/((\r?\n)|(\r\n?))/", $contents) as $line){
            $arr = explode("=", $line, 2);
            $propertyName = $arr[0];
            if($propertyName == $property)
                return true;
        }
        return false;
    }

    private function convertGameFlavorCoverImgToIcon(ResourceFile $coverImgFile) {
        $coverImgFullFilePath = storage_path('app/' . $coverImgFile->file_path);
        //we need to get the file name of the cover img (the string after the last slash of the full path)
        $coverImgFileName = substr($coverImgFullFilePath, strrpos($coverImgFullFilePath, '/') + 1);
        //now we need to get the directory that the image is located, so that we can cd into this directory and conver the image
        $coverImgFileDirectory = substr($coverImgFullFilePath, 0,strrpos($coverImgFullFilePath, '/'));
        $imgManager = new ImgManager();
        $imgManager->covertImgToIco($coverImgFileDirectory, $coverImgFileName, "game_icon.ico");

    }

    /**
     * This method retrieves the setup exe file for the given game flavor and
     * returns the file to be downloaded.
     *
     * @param $gameFlavorId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws FileNotFoundException if the file is not found
     */
    public function getWindowsSetupFileForGameFlavor($gameFlavorId) {
        $file = storage_path('app/data_packs/additional_pack_'. $gameFlavorId . '/Output/memor-i-setup.exe');
        if(!File::exists($file)) {
            throw new FileNotFoundException("The setup file for this game could not be found");
        }
        return $file;
    }

    public function getLinuxSetupFileForGameFlavor($gameFlavorId) {
        $file = storage_path('app/data_packs/additional_pack_'. $gameFlavorId . '/memori.jar');
        if(!File::exists($file)) {
            throw new FileNotFoundException("The linux file for this game could not be found");
        }
        return $file;
    }

    /**
     * This method retrieves a game flavor by its id and clones it.
     * By cloning, we mean the copy of both the DB tables that are associated with the flavor
     * (such as equivalence sets, cards, resources) as well as the data pack files that are associated
     * with the game flavor
     *
     * @param $gameFlavorId
     */
    public function cloneGameFlavorAndFiles($gameFlavorId) {
        $user = Auth::user();
        $gameFlavor = $this->gameFlavorStorage->getGameFlavorById($gameFlavorId);
        DB::transaction(function () use ($user, $gameFlavor) {
            $resourceManager = new ResourceManager();
            $newGameFlavor = $this->cloneGameFlavor($user->id, $gameFlavor);
            $coverImgFile = $resourceManager->getFileForResourceForGameFlavor($gameFlavor->coverImg, $gameFlavor->id);
            $resourceManager->cloneResourceFile($gameFlavor->coverImg, $coverImgFile, $newGameFlavor->id);

            $this->cloneDataPackFiles($gameFlavor, $newGameFlavor);
            $this->cloneDataPackResourceFileRows($gameFlavor, $newGameFlavor);
            $equivalenceSetManager = new EquivalenceSetManager();
            $equivalenceSetManager->cloneEquivalenceSetsAndCardsForGameFlavor($gameFlavor->id, $newGameFlavor->id);
        });

    }

    public function cloneGameFlavor($userId, GameFlavor $gameFlavor) {
        $newGameFlavor = $gameFlavor->replicate();
        $newGameFlavor->name .= '_copy';
        $newGameFlavor->creator_id = $userId;
        $newGameFlavor->published = false;
        $newGameFlavor->game_identifier .= $this->generateRandomString(3);
        $newGameFlavor->save();
        return $newGameFlavor;
    }

    private function cloneDataPackFiles(GameFlavor $gameFlavor, GameFlavor $newGameFlavor) {
        $sourceDir = $this->getDataPackDir($gameFlavor->id);
        $destinationDir = $this->getDataPackDir($newGameFlavor->id);

        $success = File::copyDirectory($sourceDir, $destinationDir);
        if(!$success)
            throw new Exception("Error while copying data pack files");
    }

    private function cloneDataPackResourceFileRows(GameFlavor $gameFlavor, GameFlavor $newGameFlavor) {
        $resourceCategoriesForGameFlavor = $this->getResourceCategoriesForGameFlavor($gameFlavor, $gameFlavor->interface_lang_id);
        $resourceManager = new ResourceManager();
        foreach ($resourceCategoriesForGameFlavor as $category) {
            $currCatResources = $category->resources;
            foreach ($currCatResources as $resource) {
                $resourceFile = $resourceManager->getFileForResourceForGameFlavor($resource, $gameFlavor->id);
                if($resourceFile != null) {
                    $resourceManager->cloneResourceFile($resource, $resourceFile, $newGameFlavor->id);
                }
            }
        }
    }

    public function getDataPackDir($gameFlavorId) {
        return storage_path('app/data_packs/additional_pack_' . $gameFlavorId . '/data');
    }

    /**
     * Marks this gam flavor to have a null game_status_id to indicate that it is not in the
     * "games to be approved or disapproved" list.
     * @param $id int the game flavor id
     */
    public function markGameFlavorAsNotSubmittedForApproval($id) {
        $gameFlavor = $this->getGameFlavor($id);
        $gameFlavor->submitted_for_approval = false;
        $this->gameFlavorStorage->storeGameFlavor($gameFlavor);
    }

    public function markGameFlavorAsSubmittedForApproval($id) {
        $gameFlavor = $this->getGameFlavor($id);
        $gameFlavor->submitted_for_approval = true;
        $this->gameFlavorStorage->storeGameFlavor($gameFlavor);
    }

    public function getGameFlavorsSubmittedForApproval() {
        $gameFlavorViewModels = new Collection();
        $gameFlavors = $this->gameFlavorStorage->gatGameFlavorsBySubmittedState(true);
        foreach ($gameFlavors as $gameFlavor) {
            $gameFlavorViewModels->add($this->getGameFlavorViewModel($gameFlavor->id));
        }
        return $gameFlavorViewModels;
    }

    public function sendEmailForGameSubmissionToAdmin($gameFlavorId) {
        $gameFlavor = $this->getGameFlavor($gameFlavorId);
        $mailManager = new MailManager();
        $mailManager->sendEmailToSpecificEmail('email.game_flavor_submission_admin', ['gameFlavor' => $gameFlavor], 'New Game submission: "' . $gameFlavor->name . '"', 'paulisaris@gmail.com');
    }

    public function sendEmailForGameSubmissionToCreator($gameFlavorId) {
        $gameFlavor = $this->getGameFlavor($gameFlavorId);
        $mailManager = new MailManager();
        $mailManager->sendEmailToSpecificEmail('email.game_flavor_submission_creator', ['gameFlavor' => $gameFlavor], 'Thanks for submitting your game: "' . $gameFlavor->name . '"', $gameFlavor->creator->email);
    }

    public function sendCongratulationsEmailToGameCreator($gameFlavorId) {
        $gameFlavor = $this->getGameFlavor($gameFlavorId);
        $mailManager = new MailManager();
        $mailManager->sendEmailToSpecificEmail('email.game_flavor_approved', ['gameFlavor' => $gameFlavor], 'Game "' . $gameFlavor->name . '" approved!', $gameFlavor->creator->email);
    }

    public function markGameFlavorAsBuilt($gameFlavorId) {
        $gameFlavor = $this->getGameFlavor($gameFlavorId);
        $gameFlavor->is_built = true;
        $this->gameFlavorStorage->storeGameFlavor($gameFlavor);
    }

    public function getFlavorIdFromIdentifier($gameFlavorIdentifier) {
        $gameFlavor = $this->gameFlavorStorage->getGameFlavorByGameIdentifier($gameFlavorIdentifier);
        if($gameFlavor)
            return $gameFlavor->id;
        else
            throw new \Exception("Game Flavor not found. Identifier: " . $gameFlavorIdentifier);
    }

    public function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

}
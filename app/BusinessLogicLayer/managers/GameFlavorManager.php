<?php

namespace App\BusinessLogicLayer\managers;

use App\BusinessLogicLayer\WindowsBuilder;
use App\Models\GameFlavor;
use App\Models\ResourceFile;
use App\Models\User;
use App\StorageLayer\GameFlavorStorage;
use App\StorageLayer\LanguageStorage;
use App\StorageLayer\UserStorage;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
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
    private $fileManager;
    private $userStorage;
    private $resourceCategoryManager;
    private $resourceManager;
    private $gameVersionLanguageManager;
    private $gameVersionManager;
    private $equivalenceSetManager;
    private $windowsBuilder;
    private $languageStorage;
    private $imgManager;

    public function __construct(GameFlavorStorage  $gameFlavorStorage, FileManager $fileManager,
                                UserStorage        $userStorage, ResourceCategoryManager $resourceCategoryManager,
                                ResourceManager    $resourceManager, GameVersionLanguageManager $gameVersionLanguageManager,
                                GameVersionManager $gameVersionManager, EquivalenceSetManager $equivalenceSetManager,
                                WindowsBuilder     $windowsBuilder, LanguageStorage $languageStorage,
                                ImgManager         $imgManager) {
        $this->gameFlavorStorage = $gameFlavorStorage;
        $this->fileManager = $fileManager;
        $this->userStorage = $userStorage;
        $this->resourceCategoryManager = $resourceCategoryManager;
        $this->resourceManager = $resourceManager;
        $this->gameVersionLanguageManager = $gameVersionLanguageManager;
        $this->gameVersionManager = $gameVersionManager;
        $this->equivalenceSetManager = $equivalenceSetManager;
        $this->windowsBuilder = $windowsBuilder;
        $this->languageStorage = $languageStorage;
        $this->imgManager = $imgManager;
    }

    public function getJarFilePathForGameFlavor($gameFlavorId): string {
        return storage_path() . '/app/data_packs/additional_pack_' . $gameFlavorId . '/memori.jar';
    }

    /**
     * @param $gameFlavorId int id of the game flavor
     * @param array $inputFields contain the game flavor parameters
     * @return GameFlavor the newly created instance
     * @throws \Exception
     * @internal param Request $request the request object
     */
    public function createOrUpdateGameFlavor(int $gameFlavorId, array $inputFields): GameFlavor {

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
        DB::transaction(function () use ($gameFlavor, $inputFields) {
            $gameFlavor = $this->gameFlavorStorage->storeGameFlavor($gameFlavor);
            if (!$gameFlavor->game_identifier)
                $gameFlavor->game_identifier = $this->createIdentifierForGameFlavor($gameFlavor);
            $gameFlavor = $this->gameFlavorStorage->storeGameFlavor($gameFlavor);

            if (isset($inputFields['cover_img'])) {
                $gameFlavor->cover_img_id = $this->imgManager->uploadGameFlavorCoverImg($gameFlavor, $inputFields['cover_img']);
                $gameFlavorImgCoverFile = $this->resourceManager->getFileForResourceForGameFlavor($gameFlavor->coverImg, $gameFlavor->id);
                $this->convertGameFlavorCoverImgToIcon($gameFlavorImgCoverFile);
            }
            $gameFlavor->save();
        });
        return $gameFlavor;

    }

    /**
     * @throws \Exception
     */
    public function assignGameFlavorToGameVersion($gameFlavorId, $gameVersionId): GameFlavor {
        $gameFlavor = $this->getGameFlavor($gameFlavorId);
        $gameVersion = $this->gameVersionManager->getGameVersion($gameVersionId);

        if (!$gameFlavor || !$gameVersion)
            throw new \Exception("ERROR game flavor id: " . $gameFlavorId . " or game version id: " . $gameVersionId . " do not exist");
        $gameFlavor->game_version_id = $gameVersion->id;
        $gameFlavor->save();
        return $gameFlavor;
    }

    private function createIdentifierForGameFlavor(GameFlavor $gameFlavor): string {
        return greeklish($gameFlavor->name) . '_' . $gameFlavor->id;
    }

    public function getResourceCategoriesForGameFlavor($gameFlavor, $langId): Collection {
        $gameVersionResourceCategories = $this->resourceCategoryManager->getResourceCategoriesForGameVersionForLanguage($gameFlavor->game_version_id, $langId);
        foreach ($gameVersionResourceCategories as $category) {
            $currCatResources = $category->resources;
            foreach ($currCatResources as $resource) {
                $resourceFile = $this->resourceManager->getFileForResourceForGameFlavor($resource, $gameFlavor->id);
                if ($resourceFile != null) {
                    $resource->file_path = $resourceFile->file_path;
                } else {
                    if (File::exists(storage_path('app/game_versions/data/' . $gameFlavor->game_version_id . '/' . $resource->name))) {
                        $resource->file_path = 'game_versions/data/' . $gameFlavor->game_version_id . '/' . $resource->name;
                    } else {
                        $resource->file_path = null;
                    }
                }
            }
        }
        return $gameVersionResourceCategories;
    }

    public function getGameFlavors(int $userId, $language_id): Collection {
        $user = null;
        try {
            $user = $this->userStorage->get($userId);
            if ($user->isAdmin()) {
                //if admin, get all game versions
                $gameFlavorsToBeReturned = $this->gameFlavorStorage->getGameFlavors(false, null, $language_id);
            } else {
                //if regular user, merge the published game versions with the game versions created by the user
                $publishedGameFlavors = $this->gameFlavorStorage->getGameFlavors(true, null, $language_id);
                $gameFlavorsCreatedByUser = $this->gameFlavorStorage->getGameFlavors(false, $user->id, $language_id);
                $gameFlavorsToBeReturned = $gameFlavorsCreatedByUser->merge($publishedGameFlavors);
            }
        } catch (ModelNotFoundException $e) {
            $gameFlavorsToBeReturned = $this->gameFlavorStorage->getGameFlavors(true, null, $language_id);
        } finally {
            foreach ($gameFlavorsToBeReturned as $gameFlavor) {
                $gameFlavor->accessed_by_user = $this->isGameFlavorAccessedByUser($gameFlavor->user_creator_id, $user);
            }
            return $gameFlavorsToBeReturned;
        }

    }

    private function getGameFlavorCoverImgFilePath(GameFlavor $gameFlavor) {
        $resource = $gameFlavor->coverImg;
        if ($resource != null) {
            $resourceFile = $this->resourceManager->getFileForResourceForGameFlavor($resource, $gameFlavor->id);
            if ($resourceFile != null)
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
    public function getGameFlavor($id): ?GameFlavor {
        $user = Auth::user();
        $gameFlavor = $this->gameFlavorStorage->getGameFlavorById($id);

        //if the game Version exists, check if the user has access
        if ($gameFlavor != null) {
            if ($this->isGameFlavorAccessedByUser($gameFlavor->creator->id, $user))
                return $gameFlavor;
        } else {
            throw new \Exception("Game flavor not found. Id queried: " . $id);
        }

        return null;
    }

    /**
     * @throws \Exception
     */
    public function getGameFlavorByGameIdentifier($gameFlavorIdentifier) {
        $gameFlavor = $this->gameFlavorStorage->getGameFlavorByGameIdentifier($gameFlavorIdentifier);
        if (!$gameFlavor) {
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
     * @throws ModelNotFoundException if no game flavor found by the given id
     */
    public function getGameFlavorViewModel($id): GameFlavor {
        $user = Auth::user();
        $gameFlavor = $this->gameFlavorStorage->getGameFlavorById($id);
        //if the game Version exists, check if the user has access
        if ($gameFlavor != null) {
            $gameFlavor->accessed_by_user = $this->isGameFlavorAccessedByUser($gameFlavor->creator->id, $user);
            $gameFlavor->cover_img_file_path = $this->getGameFlavorCoverImgFilePath($gameFlavor);
        } else {
            throw new ModelNotFoundException("Game flavor not found");
        }

        return $gameFlavor;
    }


    private function assignValuesToGameFlavor(GameFlavor $gameFlavor, $gameFlavorFields): GameFlavor {
        $gameFlavor->name = $gameFlavorFields['name'];
        $gameFlavor->description = $gameFlavorFields['description'];
        $gameFlavor->lang_id = $gameFlavorFields['lang_id'];
        $gameFlavor->interface_lang_id = $this->gameVersionLanguageManager->getFirstLanguageAvailableForGameVersion($gameFlavorFields['game_version_id'])->lang_id;
        $gameFlavor->copyright_link = $gameFlavorFields['copyright_link'];
        if (isset($gameFlavorFields['allow_clone']))
            $gameFlavor->allow_clone = true;
        else
            $gameFlavor->allow_clone = false;

        return $gameFlavor;
    }

    /**
     * @param $gameFlavorId . The id of the game version to be deleted
     * @return bool. True if the game version was deleted successfully, false if the user has no access
     * @throws \Exception
     */
    public function deleteGameFlavor($gameFlavorId): bool {
        $gameFlavor = $this->getGameFlavor($gameFlavorId);
        if ($gameFlavor == null)
            return false;
        $this->gameFlavorStorage->deleteGameFlavor($gameFlavor);
        return true;
    }

    /**
     * Checks if a game Version object is accessed by a user
     * (If user is admin or has created it, then they should have access, otherwise they should not)
     *
     * @param $user_creator_id int
     * @param $user User
     * @return bool user access
     */
    private function isGameFlavorAccessedByUser(int $user_creator_id, $user): bool {
        if ($user == null)
            return false;
        if ($user->isAdmin())
            return true;
        if ($user_creator_id == $user->id)
            return true;
        return false;
    }

    /**
     * Toggles the @see GameFlavor instance's published attribute
     *
     * @param $gameFlavorId int the id of the @see GameFlavor to be updated
     * @return bool if the update process was successful or not
     * @throws \Exception
     */
    public function toggleGameFlavorPublishedState(int $gameFlavorId): bool {
        $gameFlavor = $this->getGameFlavor($gameFlavorId);
        if ($gameFlavor == null)
            return false;
        $gameFlavor->published = !$gameFlavor->published;
        if ($this->gameFlavorStorage->storeGameFlavor($gameFlavor) != null) {
            return true;
        }
        return false;
    }

    /**
     * @throws \Exception
     */
    public function packageFlavor($gameFlavorId) {
        //create resources map file
        $this->resourceManager->createStaticResourcesMapFile($gameFlavorId);
        $this->resourceManager->createAdditionalPropertiesFile($this->getGameFlavor($gameFlavorId));
        //create card .json files (for equivalent sets)
        $this->equivalenceSetManager->prepareEquivalenceSets($gameFlavorId);
        $this->equivalenceSetManager->createEquivalenceSetsJSONFile($gameFlavorId, false);
        $this->equivalenceSetManager->createEquivalenceSetsJSONFile($gameFlavorId, true);
        $gameFlavor = $this->getGameFlavorViewModel($gameFlavorId);
        $this->copyGameVersionJarFileToDataPackDir($gameFlavor);
        $this->addDataPackIntoJar($gameFlavorId);
        $this->windowsBuilder->buildGameFlavorForWindows($gameFlavor, $this->getJarFilePathForGameFlavor($gameFlavorId));
    }

    /**
     * @param $gameFlavor GameFlavor the @see GameFlavor
     * @throws \Exception if the .jar file cannot be copied to the destination path
     */
    private function copyGameVersionJarFileToDataPackDir(GameFlavor $gameFlavor) {
        $sourceFile = $this->gameVersionManager->getGameVersionJarFile($gameFlavor->game_version_id);
        $destinationFile = storage_path() . '/app/data_packs/additional_pack_' . $gameFlavor->id . '/memori.jar';
        $this->fileManager->copyFileToDestinationAndReplace($sourceFile, $destinationFile);
    }

    private function addDataPackIntoJar($gameFlavorId): void {
        $old_path = getcwd();
        chdir(storage_path() . '/app/data_packs/additional_pack_' . $gameFlavorId);
        // if the game version .jar is already a game flavor build, there is already a project_additional.properties
        // inside the .jar file. We need to get this file and append it's contents to the project_additional.properties
        // file for this game flavor.
        // then we delete the project_additional.properties file and we add the newly created one
        $existingAdditionalPropertiesFileContents = shell_exec('unzip -p memori.jar project_additional.properties');
        if ($existingAdditionalPropertiesFileContents) {
            $this->replaceAdditionalPropertiesFileForGameFlavor($gameFlavorId, $existingAdditionalPropertiesFileContents);
        }
        $command = 'zip -ur memori.jar project_additional.properties data/*';
        $output = shell_exec($command);

        chdir($old_path);
    }

    private function replaceAdditionalPropertiesFileForGameFlavor($gameFlavorId, $existingAdditionalPropertiesFileContents) {
        $pathToPropsFile = 'data_packs/additional_pack_' . $gameFlavorId . '/' . 'project_additional.properties';
        foreach (preg_split("/((\r?\n)|(\r\n?))/", $existingAdditionalPropertiesFileContents) as $line) {
            if ($line) {
                $arr = explode("=", $line, 2);
                $propertyName = $arr[0];
                if (!$this->propertyExistsInPropertiesFile($propertyName, $pathToPropsFile)) {
                    Storage::append($pathToPropsFile, $line);
                }
            }
        }
    }

    private function propertyExistsInPropertiesFile($property, $pathToPropsFile): bool {
        $contents = Storage::get($pathToPropsFile);
        foreach (preg_split("/((\r?\n)|(\r\n?))/", $contents) as $line) {
            $arr = explode("=", $line, 2);
            $propertyName = $arr[0];
            if ($propertyName == $property)
                return true;
        }
        return false;
    }

    private function convertGameFlavorCoverImgToIcon(ResourceFile $coverImgFile) {
        $coverImgFullFilePath = storage_path('app/' . $coverImgFile->file_path);
        //we need to get the file name of the cover img (the string after the last slash of the full path)
        $coverImgFileName = substr($coverImgFullFilePath, strrpos($coverImgFullFilePath, '/') + 1);
        //now we need to get the directory that the image is located, so that we can cd into this directory and conver the image
        $coverImgFileDirectory = substr($coverImgFullFilePath, 0, strrpos($coverImgFullFilePath, '/'));
        $this->imgManager->covertImgToIco($coverImgFileDirectory, $coverImgFileName, "game_icon.ico");
    }

    /**
     * This method retrieves the setup exe file for the given game flavor and
     * returns the file to be downloaded.
     *
     * @param $gameFlavorId
     * @return string
     * @throws FileNotFoundException if the file is not found
     */
    public function getWindowsSetupFileForGameFlavor($gameFlavorId): string {
        $file = storage_path('app/data_packs/additional_pack_' . $gameFlavorId . '/Output/memor-i-setup.exe');
        if (!File::exists($file)) {
            throw new FileNotFoundException("The setup file for this game could not be found");
        }
        return $file;
    }

    /**
     * @throws FileNotFoundException
     */
    public function getLinuxSetupFileForGameFlavor($gameFlavorId): string {
        $file = storage_path('app/data_packs/additional_pack_' . $gameFlavorId . '/memori.jar');
        if (!File::exists($file)) {
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
            $newGameFlavor = $this->cloneGameFlavor($user->id, $gameFlavor);
            $coverImgFile = $this->resourceManager->getFileForResourceForGameFlavor($gameFlavor->coverImg, $gameFlavor->id);
            $this->resourceManager->cloneResourceFile($gameFlavor->coverImg, $coverImgFile, $newGameFlavor->id);

            $this->cloneDataPackFiles($gameFlavor, $newGameFlavor);
            $this->cloneDataPackResourceFileRows($gameFlavor, $newGameFlavor);
            $this->equivalenceSetManager->cloneEquivalenceSetsAndCardsForGameFlavor($gameFlavor->id, $newGameFlavor->id);
        });

    }

    public function cloneGameFlavor($userId, GameFlavor $gameFlavor): GameFlavor {
        $newGameFlavor = $gameFlavor->replicate();
        $newGameFlavor->name .= '_copy';
        $newGameFlavor->creator_id = $userId;
        $newGameFlavor->published = false;
        $newGameFlavor->game_identifier .= generateRandomString(3);
        $newGameFlavor->save();
        return $newGameFlavor;
    }

    private function cloneDataPackFiles(GameFlavor $gameFlavor, GameFlavor $newGameFlavor) {
        $sourceDir = $this->getDataPackDir($gameFlavor->id);
        $destinationDir = $this->getDataPackDir($newGameFlavor->id);

        $success = File::copyDirectory($sourceDir, $destinationDir);
        if (!$success)
            throw new Exception("Error while copying data pack files");
    }

    private function cloneDataPackResourceFileRows(GameFlavor $gameFlavor, GameFlavor $newGameFlavor) {
        $resourceCategoriesForGameFlavor = $this->getResourceCategoriesForGameFlavor($gameFlavor, $gameFlavor->interface_lang_id);
        foreach ($resourceCategoriesForGameFlavor as $category) {
            $currCatResources = $category->resources;
            foreach ($currCatResources as $resource) {
                $resourceFile = $this->resourceManager->getFileForResourceForGameFlavor($resource, $gameFlavor->id);
                if ($resourceFile != null) {
                    $this->resourceManager->cloneResourceFile($resource, $resourceFile, $newGameFlavor->id);
                }
            }
        }
    }

    public function getDataPackDir($gameFlavorId): string {
        return storage_path('app/data_packs/additional_pack_' . $gameFlavorId . '/data');
    }

    /**
     * Marks this gam flavor to have a null game_status_id to indicate that it is not in the
     * "games to be approved or disapproved" list.
     * @param $id int the game flavor id
     * @throws \Exception
     */
    public function markGameFlavorAsNotSubmittedForApproval(int $id) {
        $gameFlavor = $this->getGameFlavor($id);
        $gameFlavor->submitted_for_approval = false;
        $this->gameFlavorStorage->storeGameFlavor($gameFlavor);
    }

    public function markGameFlavorAsSubmittedForApproval($id) {
        $gameFlavor = $this->getGameFlavor($id);
        $gameFlavor->submitted_for_approval = true;
        $this->gameFlavorStorage->storeGameFlavor($gameFlavor);
    }

    public function getGameFlavorsSubmittedForApproval(): Collection {
        $gameFlavorViewModels = new Collection();
        $gameFlavors = $this->gameFlavorStorage->getGameFlavorsBySubmittedState(true);
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

    public function sendCongratulationsEmailToGameCreator(int $gameFlavorId) {
        $gameFlavor = $this->getGameFlavor($gameFlavorId);
        $mailManager = new MailManager();
        $mailManager->sendEmailToSpecificEmail('email.game_flavor_approved', ['gameFlavor' => $gameFlavor], 'Game "' . $gameFlavor->name . '" approved!', $gameFlavor->creator->email);
    }

    public function markGameFlavorAsBuilt($gameFlavorId) {
        $gameFlavor = $this->getGameFlavor($gameFlavorId);
        $gameFlavor->is_built = true;
        $this->gameFlavorStorage->storeGameFlavor($gameFlavor);
    }

    /**
     * @throws \Exception
     */
    public function getFlavorIdFromIdentifier(string $gameFlavorIdentifier) {
        $gameFlavor = $this->gameFlavorStorage->getGameFlavorByGameIdentifier($gameFlavorIdentifier);
        if ($gameFlavor)
            return $gameFlavor->id;
        else
            throw new \Exception("Game Flavor not found. Identifier: " . $gameFlavorIdentifier);
    }

    public function getGameFlavorsForCriteria(string $lang_code): Collection {
        $language = $this->languageStorage->getLanguageByCode($lang_code);
        $game_flavors = $this->gameFlavorStorage->getGameFlavorsForCriteria($language->id);
        foreach ($game_flavors as $game_flavor) {
            $game_flavor->base_path = route('resolveDataPath', ['filePath' => 'data_packs/additional_pack_' . $game_flavor->id . '/data/']);
            $game_flavor->cover_img_file_path = route('resolveDataPath', ['filePath' => $game_flavor->cover_img_file_path]);
            $game_flavor->equivalence_set_file_path = route('resolveDataPath', ['filePath' => $this->equivalenceSetManager->getEquivalenceSetFilePath($game_flavor->id, true)]);
        }
        return $game_flavors;
    }

}

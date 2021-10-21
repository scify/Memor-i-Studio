<?php

namespace App\BusinessLogicLayer\managers;

use App\Models\GameVersion;
use App\Models\User;
use App\StorageLayer\FileStorage;
use App\StorageLayer\GameVersionStorage;
use Madnest\Madzipper\Madzipper;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class GameVersionManager {

    private $gameVersionStorage;
    private $fileStorage;
    private $coverImgStoragePath = 'game_versions/img/';
    private $gameJarStoragePath = 'game_versions/jar/';
    private $gameExeStoragePath = 'game_versions/exe/';
    private $gameResourcesDirsSchema;
    private $gameResourcesFilesSchema;

    public function __construct() {
        $this->gameVersionStorage = new GameVersionStorage();
        $this->fileStorage = new FileStorage();
        $this->gameResourcesDirsSchema = array();
        $this->gameResourcesFilesSchema = array();
    }

    /**
     * Creates a new @see GameVersion, stores the resources zip/jar file, and creates the resources schema
     *
     * @param array $input the input parameters
     * @param User $user the user creating the Game version
     * @return GameVersion the newly created GameVersion instance
     */
    public function createGameVersion(array $input, User $user) {
        $newGameVersion = new GameVersion();
        $newGameVersion->name = $input['name'];
        $newGameVersion->version_code = $input['version_code'];
        $newGameVersion->description = $input['description'];
        $newGameVersion->creator_id = $user->id;
        if(isset($input['online']))
            $newGameVersion->online = true;
        else
            $newGameVersion->online = false;

        if (isset($input['cover_img'])) {
            $newGameVersion->cover_img_path = $this->fileStorage->storeFile($input['cover_img'], $this->coverImgStoragePath);
        }

        $newGameVersion = $this->gameVersionStorage->storeGameVersion($newGameVersion);
        $zipFilePath = $this->storeZipFile($newGameVersion->id, $input['gameResPack']);
        $this->extractResourceDirectoriesFromZipFile($zipFilePath);
        $this->extractFilesFromZipFile($zipFilePath, $newGameVersion->id);
        $resourceCategoriesManager = new ResourceCategoryManager();
        $resourcesManager = new ResourceManager();
        $resourceCategoriesManager->createResourceCategoriesFromResourcesArray($this->gameResourcesDirsSchema, $newGameVersion->id);
        $resourcesManager->createResourcesFromResourcesArray($this->gameResourcesFilesSchema, $newGameVersion->id);

        return $newGameVersion;
    }

    public function getResourcesForGameVersion(GameVersion $gameVersion) {
        $resourceCategoriesForGameVersion = $gameVersion->resourceCategories;
        $resources = new Collection();
        foreach ($resourceCategoriesForGameVersion as $resourceCategory) {
            foreach ($resourceCategory->resources as $resource) {
                $resources->add($resource);
            }
        }
        return $resources;
    }

    /**
     * Fetches all the @see GameVersion instances
     *
     * @return Collection with all the instances
     */
    public function getAllGameVersions() {
        $gameVersions = $this->gameVersionStorage->getAllGameVersions()->sortBy("created_at");
        return $gameVersions;
    }

    /**
     * Fetches a particular @see GameVersion instance (or null)
     *
     * @param $id
     * @return mixed the instance meeting the criteria, or null
     */
    public function getGameVersion($id) {
        return $this->gameVersionStorage->getGameVersionById($id);
    }

    /**
     * Sets the parameters to a @see GameVersion and updates
     *
     * @param $id int the id of the @see GameVersion to be updated.
     * @param array $input the input parameters
     * @return GameVersion the newly updated game version instance
     */
    public function editGameVersion($id, array $input) {
        $gameVersionToBeUpdated = $this->getGameVersion($id);
        $gameVersionToBeUpdated->name = $input['name'];
        $gameVersionToBeUpdated->version_code = $input['version_code'];
        $gameVersionToBeUpdated->description = $input['description'];
        if(isset($input['online']))
            $gameVersionToBeUpdated->online = true;
        else
            $gameVersionToBeUpdated->online = false;
        if (isset($input['cover_img'])) {
            $gameVersionToBeUpdated->cover_img_path = $this->fileStorage->storeFile($input['cover_img'], $this->coverImgStoragePath);
        }
        if (isset($input['gameResPack'])) {
            $zipFilePath = $this->storeZipFile($id, $input['gameResPack']);
            $this->deleteGameVersionDataDirectory($id);
            $this->extractResourceDirectoriesFromZipFile($zipFilePath);
            $this->extractFilesFromZipFile($zipFilePath, $id);
        }
        $editedGameVersion =  $this->gameVersionStorage->storeGameVersion($gameVersionToBeUpdated);

        DB::transaction(function() use($editedGameVersion, $input) {
            $resourceCategoriesManager = new ResourceCategoryManager();
            $resourceManager = new ResourceManager();
            if (isset($input['gameResPack'])) {
                $resourceCategoriesManager->editResourceCategoriesFromResourcesArray($this->gameResourcesDirsSchema, $editedGameVersion->id);
                $resourceManager->editResourcesFromResourcesArray($this->gameResourcesFilesSchema, $editedGameVersion->id);
            }
            $resourceManager->updateResourceOrdering($this->getResourcesForGameVersion($editedGameVersion));
        });
        return $editedGameVersion;
    }



    /**
     * @param $id int the @see GameVersion id
     * @param UploadedFile $gameJarFile the file to be uploaded
     * @return string the path of the uploaded file
     */
    private function storeZipFile($id, UploadedFile $gameJarFile) {
        $filePath = storage_path('app/' . $this->gameJarStoragePath . $id . '/' . 'memori.jar');
        $gameJarFile->storeAs($this->gameJarStoragePath . $id . '/', 'memori.jar');
        return $filePath;
    }

    public function getGameVersionJarFile($id) {
        $filePath = storage_path('app/' . $this->gameJarStoragePath . $id . '/' . 'memori.jar');
        return $filePath;
    }

    public function getGameVersionExeFile($id) {
        $filePath = storage_path('app/' . $this->gameExeStoragePath . $id . '/' . 'memori.exe');
        return $filePath;
    }

    /**
     * Given a zip file path, extracts it's game resources
     *
     * @param $filePath
     */
    private function extractResourceDirectoriesFromZipFile($filePath) {
        $zip = zip_open($filePath);

        if ($zip) {
            while ($entry = zip_read($zip)) {
                $entryName = zip_entry_name($entry);
                $isDir = substr($entryName, -1) == DIRECTORY_SEPARATOR;
                $this->parseEntry($isDir, $entryName);
            }
            zip_close($zip);
        }
    }

    private function extractFilesFromZipFile($zipFilePath, $gameVersionId) {
        $zipper = new Madzipper();
        $result = File::makeDirectory($this->getPathForGameVersionDataFiles($gameVersionId), 0777, true);
        $zipper->make($zipFilePath)->folder('scify_pack')->extractTo(storage_path('app/game_versions/data/' . $gameVersionId));
    }

    private function getPathForGameVersionDataFiles($gameVersionId) {
        return storage_path('app/game_versions/data/' . $gameVersionId);
    }

    private function deleteGameVersionDataDirectory($gameVersionId) {
        $success = File::deleteDirectory($this->getPathForGameVersionDataFiles($gameVersionId));
    }

    /**
     * Decides whether the entry should be considered as a resource
     *
     * @param $isDir bool if the current entry is a directory or a file
     * @param $entryName string the current file or folder in the .zip
     */
    private function parseEntry($isDir, $entryName) {
        if ($this->entryIsApplicableForAudioResource($entryName)) {
            if ($isDir)
                array_push($this->gameResourcesDirsSchema, substr($entryName, strlen('scify_pack/'), strlen($entryName)));
            else {
                $fileName = substr($entryName, strlen('scify_pack/'), strlen($entryName));
                $this->gameResourcesFilesSchema[$fileName] = $this->stringUntilLastSlash($fileName);
            }
        } else if ($this->entryIsApplicableForImageResource($entryName)) {
            if ($isDir)
                array_push($this->gameResourcesDirsSchema, substr($entryName, strlen('scify_pack/'), strlen($entryName)));
            else {
                $fileName = substr($entryName, strlen('scify_pack/'), strlen($entryName));
                $this->gameResourcesFilesSchema[$fileName] = $this->stringUntilLastSlash($fileName);
            }
        }
    }

    /**
     * Checks if an entry is part of the audios resources directory in the zip file
     *
     * @param $entryName string the directory or file name
     * @return bool
     */
    private function entryIsApplicableForAudioResource($entryName) {
        if (starts_with($entryName, 'scify_pack/audios/') && ($entryName != 'scify_pack/audios/')) {
//            if (strpos($entryName, 'card_sounds') === false && strpos($entryName, 'card_description_sounds') === false) {
//                return true;
//            }
            return true;
        }
        return false;
    }

    /**
     * Checks if an entry is part of the images resources directory in the zip file
     *
     * @param $entryName string the directory or file name
     * @return bool
     */
    private function entryIsApplicableForImageResource($entryName) {
        if (starts_with($entryName, 'scify_pack/img/') && ($entryName != 'scify_pack/img/')) {
//            if (strpos($entryName, 'card_images') === false) {
//                return true;
//            }
            return true;
        }
        return false;
    }

    /**
     * @param $str string the given string
     * @return string the string without the part after the last slash
     */
    private function stringUntilLastSlash($str) {
        return substr($str, 0, strrpos($str, '/')) . '/';
    }


}

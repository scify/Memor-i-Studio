<?php

namespace App\BusinessLogicLayer\managers;

use App\Models\GameVersion;
use App\Models\User;
use App\StorageLayer\FileStorage;
use App\StorageLayer\GameVersionStorage;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Madnest\Madzipper\Madzipper;

class GameVersionManager {

    private $gameVersionStorage;
    private $fileStorage;
    private $coverImgStoragePath = 'game_versions/img/';
    private $gameJarStoragePath = 'game_versions/jar/';
    private $gameExeStoragePath = 'game_versions/exe/';
    private $gameResourcesDirsSchema;
    private $gameResourcesFilesSchema;
    private $resourceCategoryManager;
    private $resourceManager;

    public function __construct(GameVersionStorage      $gameVersionStorage, FileStorage $fileStorage,
                                ResourceCategoryManager $resourceCategoryManager, ResourceManager $resourceManager) {
        $this->gameVersionStorage = $gameVersionStorage;
        $this->fileStorage = $fileStorage;
        $this->resourceCategoryManager = $resourceCategoryManager;
        $this->resourceManager = $resourceManager;
        $this->gameResourcesDirsSchema = array();
        $this->gameResourcesFilesSchema = array();
    }

    /**
     * Creates a new @param array $input the input parameters
     * @param User $user the user creating the Game version
     * @return GameVersion the newly created GameVersion instance
     * @throws Exception
     * @see GameVersion, stores the resources zip/jar file, and creates the resources schema
     *
     */
    public function createGameVersion(array $input, User $user): GameVersion {
        $newGameVersion = new GameVersion();
        $newGameVersion->name = $input['name'];
        $newGameVersion->version_code = $input['version_code'];
        $newGameVersion->description = $input['description'];
        $newGameVersion->creator_id = $user->id;
        if (isset($input['online']))
            $newGameVersion->online = true;
        else
            $newGameVersion->online = false;

        if (isset($input['cover_img'])) {
            $newGameVersion->cover_img_path = $this->fileStorage->storeFile($input['cover_img'], $this->coverImgStoragePath);
        }

        $newGameVersion = $this->gameVersionStorage->storeGameVersion($newGameVersion);
        if (isset($input['gameResPack'])) {
            $zipFilePath = $this->storeZipFile($newGameVersion->id, $input['gameResPack']);
            $this->extractResourceDirectoriesFromZipFile($zipFilePath, $newGameVersion);
            $this->extractFilesFromZipFile($zipFilePath, $newGameVersion);
            $this->resourceCategoryManager->createResourceCategoriesFromResourcesArray($this->gameResourcesDirsSchema, $newGameVersion->id);
            $this->resourceManager->createResourcesFromResourcesArray($this->gameResourcesFilesSchema, $newGameVersion->id);
        }
        return $newGameVersion;
    }

    public function getResourcesForGameVersion(GameVersion $gameVersion): Collection {
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
     * Fetches all the @return Collection with all the instances
     * @see GameVersion instances
     *
     */
    public function getAllGameVersions(): Collection {
        return $this->gameVersionStorage->getAllGameVersions()->sortBy("created_at");
    }

    /**
     * Fetches a particular @param $id
     * @return mixed the instance meeting the criteria, or null
     * @see GameVersion instance (or null)
     *
     */
    public function getGameVersion($id) {
        return $this->gameVersionStorage->getGameVersionById($id);
    }

    /**
     * Sets the parameters to a @param $id int the id of the @see GameVersion to be updated.
     * @param array $input the input parameters
     * @return GameVersion the newly updated game version instance
     * @throws Exception
     * @see GameVersion and updates
     *
     */
    public function editGameVersion(int $id, array $input): GameVersion {
        $gameVersionToBeUpdated = $this->getGameVersion($id);
        $gameVersionToBeUpdated->name = $input['name'];
        $gameVersionToBeUpdated->version_code = $input['version_code'];
        $gameVersionToBeUpdated->description = $input['description'];
        $gameVersionToBeUpdated->data_pack_dir_name = $input['data_pack_dir_name'];
        if (isset($input['online']))
            $gameVersionToBeUpdated->online = true;
        else
            $gameVersionToBeUpdated->online = false;
        if (isset($input['cover_img'])) {
            $gameVersionToBeUpdated->cover_img_path = $this->fileStorage->storeFile($input['cover_img'], $this->coverImgStoragePath);
        }
        if (isset($input['gameResPack'])) {
            $zipFilePath = $this->storeZipFile($id, $input['gameResPack']);
            $this->deleteGameVersionDataDirectory($id);
            $this->extractResourceDirectoriesFromZipFile($zipFilePath, $gameVersionToBeUpdated);
            $this->extractFilesFromZipFile($zipFilePath, $gameVersionToBeUpdated);
        }
        $editedGameVersion = $this->gameVersionStorage->storeGameVersion($gameVersionToBeUpdated);

        DB::transaction(function () use ($editedGameVersion, $input) {

            if (isset($input['gameResPack'])) {
                $this->resourceCategoryManager->editResourceCategoriesFromResourcesArray($this->gameResourcesDirsSchema, $editedGameVersion->id);
                $this->resourceManager->editResourcesFromResourcesArray($this->gameResourcesFilesSchema, $editedGameVersion->id);
            }
            $this->resourceManager->updateResourceOrdering($this->getResourcesForGameVersion($editedGameVersion));
        });
        return $editedGameVersion;
    }


    /**
     * @param $id int the @see GameVersion id
     * @param UploadedFile $gameJarFile the file to be uploaded
     * @return string the path of the uploaded file
     */
    private function storeZipFile(int $id, UploadedFile $gameJarFile): string {
        $filePath = storage_path('app/' . $this->gameJarStoragePath . $id . '/' . 'memori.jar');
        $gameJarFile->storeAs($this->gameJarStoragePath . $id . '/', 'memori.jar');
        return $filePath;
    }

    public function getGameVersionJarFile(int $id): string {
        return storage_path('app/' . $this->gameJarStoragePath . $id . '/' . 'memori.jar');
    }

    public function getGameVersionExeFile(int $id): string {
        return storage_path('app/' . $this->gameExeStoragePath . $id . '/' . 'memori.exe');
    }

    /**
     * Given a zip file path, extracts it's game resources
     *
     * @param string $filePath
     * @param GameVersion $gameVersion
     */
    private function extractResourceDirectoriesFromZipFile(string $filePath, GameVersion $gameVersion) {
        $zip = zip_open($filePath);

        if ($zip) {
            while ($entry = zip_read($zip)) {
                $entryName = zip_entry_name($entry);
                $isDir = substr($entryName, -1) == DIRECTORY_SEPARATOR;
                $this->parseEntry($isDir, $entryName, $gameVersion);
            }
            zip_close($zip);
        }
    }

    /**
     * @throws Exception
     */
    private function extractFilesFromZipFile($zipFilePath, GameVersion $gameVersion) {
        $zipper = new Madzipper();
        File::makeDirectory($this->getPathForGameVersionDataFiles($gameVersion->id), 0777, true);
        $zipper->make($zipFilePath)->folder($gameVersion->data_pack_dir_name)->extractTo(storage_path('app/game_versions/data/' . $gameVersion->id));
    }

    private function getPathForGameVersionDataFiles(int $gameVersionId): string {
        return storage_path('app/game_versions/data/' . $gameVersionId);
    }

    private function deleteGameVersionDataDirectory(int $gameVersionId) {
        File::deleteDirectory($this->getPathForGameVersionDataFiles($gameVersionId));
    }

    /**
     * Decides whether the entry should be considered as a resource
     *
     * @param $isDir bool if the current entry is a directory or a file
     * @param $entryName string the current file or folder in the .zip
     */
    private function parseEntry(bool $isDir, string $entryName, GameVersion $gameVersion) {
        if ($this->entryIsApplicableForAudioResource($entryName, $gameVersion)) {
            if ($isDir)
                array_push($this->gameResourcesDirsSchema, substr($entryName, strlen($gameVersion->data_pack_dir_name . "/"), strlen($entryName)));
            else {
                $fileName = substr($entryName, strlen($gameVersion->data_pack_dir_name . "/"), strlen($entryName));
                $this->gameResourcesFilesSchema[$fileName] = $this->stringUntilLastSlash($fileName);
            }
        } else if ($this->entryIsApplicableForImageResource($entryName)) {
            if ($isDir)
                array_push($this->gameResourcesDirsSchema, substr($entryName, strlen($gameVersion->data_pack_dir_name . "/"), strlen($entryName)));
            else {
                $fileName = substr($entryName, strlen($gameVersion->data_pack_dir_name . "/"), strlen($entryName));
                $this->gameResourcesFilesSchema[$fileName] = $this->stringUntilLastSlash($fileName);
            }
        }
    }

    /**
     * Checks if an entry is part of the audios resources directory in the zip file
     *
     * @param $entryName string the directory or file name
     * @param GameVersion $gameVersion
     * @return bool
     */
    private function entryIsApplicableForAudioResource(string $entryName, GameVersion $gameVersion): bool {
        return starts_with($entryName, $gameVersion->data_pack_dir_name . '/audios/')
            && ($entryName != $gameVersion->data_pack_dir_name . '/audios/');
    }

    /**
     * Checks if an entry is part of the images resources directory in the zip file
     *
     * @param $entryName string the directory or file name
     * @param GameVersion $gameVersion
     * @return bool
     */
    private function entryIsApplicableForImageResource(string $entryName, GameVersion $gameVersion): bool {
        return (starts_with($entryName, $gameVersion->data_pack_dir_name . '/img/')
            && ($entryName != $gameVersion->data_pack_dir_name . '/img/'));
    }

    /**
     * @param $str string the given string
     * @return string the string without the part after the last slash
     */
    private function stringUntilLastSlash(string $str): string {
        return substr($str, 0, strrpos($str, '/')) . '/';
    }


}

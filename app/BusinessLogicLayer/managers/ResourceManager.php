<?php
namespace App\BusinessLogicLayer\managers;

use App\Models\Resource;
use App\Models\ResourceTranslation;
use App\Models\ResourceFile;
use App\StorageLayer\ResourceCategoryStorage;
use App\StorageLayer\ResourceStorage;
use App\StorageLayer\ResourceTranslationStorage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

include_once 'functions.php';

/**
 * Handles the @see Resource instances and business logic methods
 */
class ResourceManager {

    private $resourceStorage;
    private $resourceCategoryStorage;
    private $resourceTranslationStorage;

    public function __construct() {
        $this->resourceStorage = new ResourceStorage();
        $this->resourceCategoryStorage = new ResourceCategoryStorage();
        $this->resourceTranslationStorage = new ResourceTranslationStorage();
    }

    /**
     * Given an @see UploadedFile, stores the file and creates a new @see Resource instance
     *
     * @param $pathToStore string the path to store the file
     * @return int the newly created instance id
     */
    public function createNewResource($pathToStore) {
        $resourceCategory = $this->resourceCategoryStorage->getResourceCategoryByPath($pathToStore);

        $resource = new Resource();
        $resource->category_id = $resourceCategory->id;

        return $this->resourceStorage->storeResource($resource);
    }

    /**
     * Given an @see UploadedFile, stores the file and creates a new @see Resource instance
     *
     * @param UploadedFile $file the file to be stored (eg image or audio)
     * @param $pathToStore string the path to store the file
     * @param $resourceId int the id if the @see Resource instance
     * @param $gameFlavorId int the id of the game flavor this resource belongs
     * @return Resource the newly created instance
     */
    public function createAndStoreResourceFile(UploadedFile $file, $pathToStore, $resourceId, $gameFlavorId) {
        $filename = $this->storeFileToPath($file, $pathToStore);

        $resourceFile = new ResourceFile();
        $resourceFile->file_path = $pathToStore . $filename;
        $resourceFile->resource_id = $resourceId;
        $resourceFile->game_flavor_id = $gameFlavorId;
        return $this->resourceStorage->storeResourceFile($resourceFile);
    }

    private function storeFileToPath(UploadedFile $file, $pathToStore) {
        $fileNamePrefix = 'res_' . milliseconds() . '_' . generateRandomString(6) . '_';
        $fileName = $fileNamePrefix . $file->getClientOriginalName();

        $convertedFileName = $fileNamePrefix . pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '.mp3';
        //temporarily store the file in order to be able to convert it
        $file->storeAs($pathToStore, $fileName);
        $this->convertFileToMp3AndStore(storage_path('app/' . $pathToStore . $fileName), storage_path('app/' . $pathToStore . $convertedFileName));

        //delete old temp file
        Storage::delete($pathToStore . $fileName);
        return $convertedFileName;
    }

    private function convertFileToMp3AndStore($filePath , $newFilePath) {
        $old_path = getcwd();
        chdir(public_path());
        $command = './convert_file_to_mp3.sh ' . $filePath . ' ' . $newFilePath;
        $output = shell_exec($command);
        chdir($old_path);
        return $output;
    }

    /**
     * Given an array with the resource file names, create corresponding resources
     *
     * @param $gameResourcesFilesSchema array containing resource file names (full resource path)
     * @param $gameVersionId int the game version id
     */
    public function createResourcesFromResourcesArray($gameResourcesFilesSchema, $gameVersionId) {
        $resourceCategoryManager = new ResourceCategoryManager();

        foreach ($gameResourcesFilesSchema as $gameResourceFile =>$resourceCategoryName) {

            $resourceCategory = $resourceCategoryManager->getResourceCategoryByNameForGameVersion($resourceCategoryName, $gameVersionId);

            //if resource category exists and it is not a dynamic resource category
            if($resourceCategory != null && $resourceCategory->type_id != 2) {
                $newResource = new Resource();
                $newResource->category_id = $resourceCategory->id;
                $newResource->name = $gameResourceFile;
                $newResource->default_text = $gameResourceFile;
                $this->resourceStorage->storeResource($newResource);
            }
        }
    }

    /**
     * Updates or creates new resources translations
     *
     * @param $resources array of resource translation parameters
     * @param $langId int the language id for the translation
     */
    public function createOrUpdateResourceTranslations(array $resources, $langId) {
        foreach ($resources as $resource) {
            $existingResourceTranslation = $this->resourceTranslationStorage->getTranslationForResource($resource['id'], $langId);
            if($existingResourceTranslation == null) {
                //create  new resource translation
                $this->createNewTranslationForResource($resource['translation'], $resource['id'], $langId);
            } else {
                //update the existing translation
                $this->updateTranslationForResource($existingResourceTranslation, $resource['translation']);
            }
        }
    }

    /**
     * Creates a new @see ResourceTranslation instance
     *
     * @param $translation string the translation message
     * @param $resourceId int the resource id
     * @param $langId int the language id
     */
    private function createNewTranslationForResource($translation, $resourceId, $langId) {
        $resourceTranslation = new ResourceTranslation();
        $resourceTranslation->description = $translation;
        $resourceTranslation->resource_id = $resourceId;
        $resourceTranslation->lang_id = $langId;
        $this->resourceTranslationStorage->saveResourceTranslation($resourceTranslation);
    }

    /**
     * Updates the description of the resource translation
     *
     * @param ResourceTranslation $existingResourceTranslation the resource transation instance
     * @param $translation string the translation message
     */
    private function updateTranslationForResource(ResourceTranslation $existingResourceTranslation, $translation) {
        $existingResourceTranslation->description = $translation;
        $this->resourceTranslationStorage->saveResourceTranslation($existingResourceTranslation);
    }

    /**
     * For a set of resources (in a given array) creates or updates their files (if uploaded)
     *
     * @param $resourceInputs
     * @param $gameFlavorId
     */
    public function createOrUpdateResourceFiles($resourceInputs, $gameFlavorId) {
        foreach ($resourceInputs as $resourceInput) {
            if(isset($resourceInput['audio'])) {
                $this->createOrUpdateResourceFile($resourceInput['audio'], $resourceInput['id'], $gameFlavorId);
            }
        }
    }

    /**
     * For a given resource, checks if it exists and creates or updates, accordingly
     *
     * @param UploadedFile $file
     * @param $resourceId
     * @param $gameFlavorId
     */
    public function createOrUpdateResourceFile(UploadedFile $file, $resourceId, $gameFlavorId) {
        $existingResourceFile = $this->resourceStorage->getFileForResource($resourceId, $gameFlavorId);
        $resource = $this->resourceStorage->getResourceById($resourceId);
        $pathToStoreResourceFile = 'data_packs/additional_pack_' . $gameFlavorId . '/data_pack_' . $gameFlavorId . '/' .substr($resource->name, 0,strrpos($resource->name, '/')) . '/';
        if($existingResourceFile == null)
            $this->createAndStoreResourceFile($file, $pathToStoreResourceFile, $resourceId, $gameFlavorId);
        else
            $this->updateResourceFile($file, $existingResourceFile, $pathToStoreResourceFile);
    }

    /**
     * Uploads and updates a resource file (deletes the old one)
     *
     * @param UploadedFile $file
     * @param ResourceFile $existingResourceFile
     * @param $pathToStoreResourceFile
     */
    private function updateResourceFile(UploadedFile $file, ResourceFile $existingResourceFile, $pathToStoreResourceFile) {
        $filename = $this->storeFileToPath($file, $pathToStoreResourceFile);
        //delete the old file
        Storage::delete($existingResourceFile->file_path);
        $existingResourceFile->file_path = $pathToStoreResourceFile . $filename;
        $this->resourceStorage->storeResourceFile($existingResourceFile);
    }

    /**
     * Gets the static resources for a game flavor (the ones that the user has edited)
     * Creates a map file containing the resource default file name and the uploaded file name
     *
     * @param $gameFlavorId int the id of the game flavor
     */
    public function createStaticResourcesMapFile($gameFlavorId) {
        $gameStaticResources = $this->resourceStorage->getResourcesForGameFlavorByResourceType($gameFlavorId, 1);
        $pathToMapFile = 'data_packs/additional_pack_' . $gameFlavorId . '/data_pack_' . $gameFlavorId . '/' . 'resources_map.properties';
        //initialise file (will overwrite all contents)

        Storage::put($pathToMapFile, null);
        foreach ($gameStaticResources as $gameStaticResource) {
            $resourceFileNameNoFile = substr($gameStaticResource->name, 0, strripos($gameStaticResource->name, '/'));
//            $resourceFileNameNoPath = substr($gameStaticResource->name, strrpos($gameStaticResource->name, '/') + 1);
            $resourceFileValueNoPath = substr($gameStaticResource->file_path, strrpos($gameStaticResource->file_path, '/') + 1);
            Storage::append($pathToMapFile, $gameStaticResource->name . "=" . $resourceFileNameNoFile . '/' . $resourceFileValueNoPath);
        }

    }

    //TODO move
    public function createAdditionalPropertiesFile($gameFlavorId) {
        $pathToPropsFile = 'data_packs/additional_pack_' . $gameFlavorId . '/' . 'project_additional.properties';
        Storage::put($pathToPropsFile, null);
        Storage::append($pathToPropsFile, "DATA_PACKAGE=" . 'data_pack_' . $gameFlavorId);
    }
}
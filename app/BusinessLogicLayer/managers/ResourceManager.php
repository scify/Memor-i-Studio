<?php
namespace App\BusinessLogicLayer\managers;

use App\Models\Resource;
use App\Models\ResourceTranslation;
use App\Models\ResourceFile;
use App\StorageLayer\ResourceCategoryStorage;
use App\StorageLayer\ResourceStorage;
use App\StorageLayer\ResourceTranslationStorage;
use Illuminate\Http\UploadedFile;

/**
 * Created by IntelliJ IDEA.
 * User: pisaris
 * Date: 12/1/2017
 * Time: 4:42 μμ
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
        $filename = 'res_' . milliseconds() . '_' . generateRandomString(6) . '_' . $file->getClientOriginalName();
        $file->storeAs($pathToStore, $filename);

        $resourceFile = new ResourceFile();
        $resourceFile->file_path = $pathToStore . $filename;
        $resourceFile->resource_id = $resourceId;
        $resourceFile->game_flavor_id = $gameFlavorId;
        return $this->resourceStorage->storeResourceFile($resourceFile);
    }

    /**
     * Given an @see UploadedFile, stores the file and creates a new @see Resource instance
     *
     * @param UploadedFile $file the file to be stored (eg image or audio)
     * @param $pathToStore string the path to store the file
     * @param $resourceId int the id if the @see Resource instance
     * @return Resource the newly created instance
     */
    public function createAndStoreStaticResourceFile($gameFlavorId, UploadedFile $file, $pathToStore, $resourceId) {
        $filename = 'res_' . milliseconds() . '_' . generateRandomString(6) . '_' . $file->getClientOriginalName();
        $file->storeAs($pathToStore, $filename);

        $staticResourceFile = new ResourceFile();
        $staticResourceFile->file_path = $pathToStore . $filename;
        $staticResourceFile->resource_id = $resourceId;
        $staticResourceFile->game_flavor_id = $gameFlavorId;
        return $this->resourceStorage->storeResource($staticResourceFile);
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

            if($resourceCategory != null) {
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

}
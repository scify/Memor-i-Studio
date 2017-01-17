<?php
namespace App\BusinessLogicLayer\managers;

use App\Models\Resource;
use App\StorageLayer\ResourceCategoryStorage;
use App\StorageLayer\ResourceStorage;
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

    public function __construct() {
        $this->resourceStorage = new ResourceStorage();
        $this->resourceCategoryStorage = new ResourceCategoryStorage();
    }

    public function createAndStoreNewResource(UploadedFile $file, $pathToStore) {
        $filename = 'res_' . milliseconds() . '_' . generateRandomString(6) . '_' . $file->getClientOriginalName();
        $resourceCategory = $this->resourceCategoryStorage->getResourceCategoryByPath($pathToStore);

        $file->storeAs($pathToStore, $filename);
        $resource = new Resource();
        $resource->category_id = $resourceCategory->id;
        $resource->file_path = $pathToStore;
        return $this->resourceStorage->storeResource($resource);
    }

    public function createResourcesFromResourcesArray($gameResourcesFilesSchema, $gameVersionId) {
        $resourceCategoryManager = new ResourceCategoryManager();
        //dd($gameResourcesFilesSchema);
        foreach ($gameResourcesFilesSchema as $gameResourceFile =>$resourceCategoryName) {
            //dd($gameResourceFile);
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

}
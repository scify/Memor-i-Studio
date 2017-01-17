<?php
namespace App\BusinessLogicLayer\managers;

use App\Models\GameVersion;
use App\Models\ResourceCategory;
use App\StorageLayer\ResourceCategoryStorage;

/**
 * Class containing the Business logic for Resource Categories
 */
class ResourceCategoryManager {

    private $resourceCategoryStorage;

    public function __construct() {
        $this->resourceCategoryStorage = new ResourceCategoryStorage();
    }

    /**
     * Given an array of resources paths, parse each path as a new @see ResourceCategory
     *
     * @param array $resources the array containing the resourcesPath
     * @param $gameVersionId int the id of the GameVersion these ResourceCategory instances will belong to
     */
    public function createResourceCategoriesFromResourcesArray(array $resources, $gameVersionId) {
        foreach ($resources as $resource) {
            $this->createNewResourceCategory($resource, $gameVersionId);
        }
    }

    /**
     * Given a category path and a @see GameVersion id, create a new @see ResourceCategory instance
     *
     * @param $categoryPath string the application path of the resource category
     * @param $gameVersionId int the id of the GameVersion these ResourceCategory instance will belong to
     * @return mixed|null the newly crated ResourceCategory instance, or null if an error occurred.
     */
    public function createNewResourceCategory($categoryPath, $gameVersionId) {
        $resourceCategory = new ResourceCategory();
        $resourceCategory->path = $categoryPath;
        $resourceCategory->game_version_id = $gameVersionId;
        $resourceCategory->description = trim($categoryPath, "/");
        return $this->resourceCategoryStorage->storeResourceCategory($resourceCategory);
    }

    /**
     * Given a Resource category name, get the instance corresponding to it.
     *
     * @param $resourceCategoryName string the resource category name
     * @param $gameVersionId int the game version id of the resource category
     * @return mixed the ResourceCategory instance, or null if no occurrences found.
     */
    public function getResourceCategoryByNameForGameVersion($resourceCategoryName, $gameVersionId) {
        return $this->resourceCategoryStorage->getResourceCategoryByPathForGameVersion($resourceCategoryName, $gameVersionId);
    }

}
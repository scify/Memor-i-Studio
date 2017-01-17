<?php
namespace App\BusinessLogicLayer\managers;

use App\Models\GameVersion;
use App\Models\Resource;
use App\Models\ResourceCategory;
use App\StorageLayer\ResourceCategoryStorage;
use App\StorageLayer\ResourceTranslationStorage;
use Illuminate\Database\Eloquent\Collection;

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

    /**
     * Gets a @see Collection of @see ResourceCategory instances for the Game Version
     *
     * @param $gameVersionId int the GameVersion id
     * @return Collection a set of the ResourceCategory instances
     */
    public function getResourceCategoriesForGameVersion($gameVersionId) {
        return $this->resourceCategoryStorage->getResourceCategoriesForGameVersion($gameVersionId);
    }

    /**
     * For a given game version id and a language id, get the corresponding @see ResourceCategory instances,
     * each containing @see Resource instances
     *
     * @param $gameVersionId int the game version id
     * @param $langId int the language id
     * @return Collection set of ResourceCategories containing resource instances
     */
    public function getResourceCategoriesForGameVersionForLanguage($gameVersionId, $langId) {
        $gameVersionResourceCategories = $this->getResourceCategoriesForGameVersion($gameVersionId);
        $resourceTranslationStorage = new ResourceTranslationStorage();
        foreach ($gameVersionResourceCategories as $category) {
            $currCatResources = $category->resources;
            foreach ($currCatResources as $resource) {
                $translationForResource = $resourceTranslationStorage->getTranslationForResource($resource->id, $langId);
                if($translationForResource != null) {
                    $resource->default_text = $translationForResource->description;
                }
            }
        }
        return $gameVersionResourceCategories;
    }

}
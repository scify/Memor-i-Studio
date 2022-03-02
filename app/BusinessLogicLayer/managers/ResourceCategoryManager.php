<?php
namespace App\BusinessLogicLayer\managers;

use App\Models\GameVersion;
use App\Models\Resource;
use App\Models\ResourceCategory;
use App\Models\ResourceCategoryTranslation;
use App\StorageLayer\ResourceCategoryStorage;
use App\StorageLayer\ResourceCategoryTranslationStorage;
use App\StorageLayer\ResourceFileStorage;
use App\StorageLayer\ResourceTranslationStorage;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class containing the Business logic for Resource Categories
 */
class ResourceCategoryManager {

    private $resourceCategoryStorage;
    private $resourceCategoryTranslationStorage;

    /**
     * We can order each of the resource categories by add an integer tied to it's path.
     * When the resource is created, it will have the ordering described here (or null of we do not describe an ordering
     * for this resource category.
     *
     * Then, the resource categories will be presented to teh user ordered by this ordering.
     *
     * @var array
     */
    private $resourceCategoryOrdering = array(
        'audios/game_instructions/' => 1,
        'audios/storyline_audios/' => 2,
        'audios/level_intro_sounds/' => 3,
        'audios/fun_factor_sounds/' => 4,
        'audios/end_level_starting_sounds/' => 5,
        'audios/end_level_ending_sounds/' => 6,
        'audios/level_name_sounds/' => 7,
        'audios/miscellaneous/' => 9,
        'audios/letters/' => 10,
        'audios/numbers/' => 11,
    );

    public function __construct(ResourceCategoryStorage $resourceCategoryStorage,
                                ResourceCategoryTranslationStorage $resourceCategoryTranslationStorage) {
        $this->resourceCategoryStorage = $resourceCategoryStorage;
        $this->resourceCategoryTranslationStorage = $resourceCategoryTranslationStorage;
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

    public function editResourceCategoriesFromResourcesArray(array $newResourceCategories, $gameVersionId) {
        $gameVersionResourceCategories = $this->getResourceCategoriesForGameVersion($gameVersionId, true);
        $resourceCategoryPaths = array();
        foreach ($gameVersionResourceCategories as $gameVersionResourceCategory) {
            $resourceCategoryPaths[] = $gameVersionResourceCategory->path;
        }
        // delete any resource categories that font exist in the new .jar file
        foreach ($gameVersionResourceCategories as $gameVersionResourceCategory) {
            if(!in_array($gameVersionResourceCategory->path, $newResourceCategories))
                $gameVersionResourceCategory->delete();
        }

        // create new resource category for any new path that is not in the old resource categories schema
        foreach ($newResourceCategories as $newResourceCategoryPath) {
            if(!in_array($newResourceCategoryPath, $resourceCategoryPaths))
                $this->createNewResourceCategory($newResourceCategoryPath, $gameVersionId);
        }
    }

    /**
     * Given a category path and a @param $categoryPath string the application path of the resource category
     * @param $gameVersionId int the id of the GameVersion these ResourceCategory instance will belong to
     * @return mixed|null the newly crated ResourceCategory instance, or null if an error occurred.
     *@see GameVersion id, create a new @see ResourceCategory instance
     *
     */
    public function createNewResourceCategory(string $categoryPath, int $gameVersionId) {
        $resourceCategory = new ResourceCategory();
        $resourceCategory->path = $categoryPath;
        $resourceCategory->game_version_id = $gameVersionId;
        $resourceCategory->description = trim($categoryPath, "/");
        if(isset($this->resourceCategoryOrdering[$categoryPath]))
            $resourceCategory->order_id = $this->resourceCategoryOrdering[$categoryPath];
        if($this->isResourceCategoryStatic($categoryPath))
            $resourceCategory->type_id = 1;
        else
            $resourceCategory->type_id = 2;
        return $this->resourceCategoryStorage->storeResourceCategory($resourceCategory);
    }

    private function isResourceCategoryStatic($categoryPath): bool {
        return !(strpos($categoryPath, 'card_sounds') !== false
            || strpos($categoryPath, 'card_description_sounds') !== false
            || strpos($categoryPath, 'card_images') !== false);
    }

    /**
     * Gets a @see Collection of @see ResourceCategory instances for the Game Version
     *
     * @param $gameVersionId int the GameVersion id
     * @return Collection a set of the ResourceCategory instances
     */
    public function getResourceCategoriesForGameVersion(int $gameVersionId, $staticResources, $langId = null): Collection {
        $resourceTypeId = $staticResources ? 1 : 2;
        return $this->resourceCategoryStorage->getResourceCategoriesForGameVersion($gameVersionId, $resourceTypeId, $langId);
    }

    /**
     * For a given game version id and a language id, get the corresponding @see ResourceCategory instances,
     * each containing @see Resource instances
     *
     * @param $gameVersionId int the game version id
     * @param $langId int the language id
     * @return Collection set of ResourceCategories containing resource instances
     */
    public function getResourceCategoriesForGameVersionForLanguage(int $gameVersionId, int $langId): Collection {
        $gameVersionResourceCategories = $this->getResourceCategoriesForGameVersion($gameVersionId, true, $langId);
        $resourceTranslationStorage = new ResourceTranslationStorage();
        $resourceCategoryTranslationStorage = new ResourceCategoryTranslationStorage();
        foreach ($gameVersionResourceCategories as $category) {
            $translationForResourceCategory = $resourceCategoryTranslationStorage->getTranslationForResourceCategory($category->id, $langId);
            if($translationForResourceCategory != null) {
                $category->description = $translationForResourceCategory->description;
            }

            $currCatResources = $category->resources;
            foreach ($currCatResources as $resource) {
                $translationForResource = $resourceTranslationStorage->getTranslationForResource($resource->id, $langId);
                if($translationForResource != null) {
                    $resource->default_text = $translationForResource->resource_name;
                    $resource->default_description = $translationForResource->resource_description;
                }
            }
        }
        return $gameVersionResourceCategories;
    }


    /**
     * Updates or creates new resource category translation
     *
     * @param $resourceCategoryId int the id of the resource category
     * @param $langId int the language id for the translation
     * @param $translationText string the translation string
     */
    public function createOrUpdateResourceCategoryTranslation(int $resourceCategoryId, int $langId, string $translationText) {
        $existingResourceCategoryTranslation = $this->resourceCategoryTranslationStorage->getTranslationForResourceCategory($resourceCategoryId, $langId);
        if($existingResourceCategoryTranslation == null) {
            //create  new resource translation
            $this->createNewTranslationForResourceCategory($translationText, $resourceCategoryId, $langId);
        } else {
            //update the existing translation
            $this->updateTranslationForResourceCategory($existingResourceCategoryTranslation, $translationText);
        }
    }

    /**
     * Creates a new @see ResourceTranslation instance
     *
     * @param $translationText string the translation message
     * @param $resourceId int the resource id
     * @param $langId int the language id
     */
    private function createNewTranslationForResourceCategory(string $translationText, int $resourceId, int $langId) {
        $resourceTranslationCategory = new ResourceCategoryTranslation();
        $resourceTranslationCategory->description = $translationText;
        $resourceTranslationCategory->resource_category_id = $resourceId;
        $resourceTranslationCategory->lang_id = $langId;
        $this->resourceCategoryTranslationStorage->saveResourceCategoryTranslation($resourceTranslationCategory);
    }

    /**
     * Updates the description of the resource translation
     *
     * @param ResourceCategoryTranslation $existingResourceCategoryTranslation the resource category translation instance
     * @param $translationText string the translation message
     */
    private function updateTranslationForResourceCategory(ResourceCategoryTranslation $existingResourceCategoryTranslation, string $translationText) {
        $existingResourceCategoryTranslation->description = $translationText;
        $this->resourceCategoryTranslationStorage->saveResourceCategoryTranslation($existingResourceCategoryTranslation);
    }

}

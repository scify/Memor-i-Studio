<?php
/**
 * Created by IntelliJ IDEA.
 * User: pisaris
 * Date: 12/1/2017
 * Time: 4:38 μμ
 */

namespace App\StorageLayer;

use App\Models\Resource;
use App\Models\ResourceFile;
use Illuminate\Support\Facades\DB;

/**
 * Class ResourceStorage
 * @package App\StorageLayer
 */
class ResourceStorage {

    /**
     * Saves a @see Resource instance
     *
     * @param Resource $resource
     * @return null
     */
    public function storeResource(Resource $resource) {
        $newResource = $resource->save();
        if($newResource)
            return $resource->id;
        return null;
    }

    /**
     * Gets a @see Resource instance by id
     *
     * @param $id int the id of the resource
     * @return mixed the instance if found, otherwise null
     */
    public function getResourceById($id) {
        return Resource::find($id);
    }

    /**
     * Gets a @see Resource instance by id
     * @return mixed the instance if found, otherwise null
     * @internal param int $id the id of the resource
     */
    public function getResourceByCategoryId($resourceCateforyId) {
        return Resource::where(['category_id' => $resourceCateforyId])->first();
    }

    /**
     * Saves a @see ResourceFile instance
     *
     * @param ResourceFile $resource
     * @return mixed|null the @see ResourceFile instance if correctly created, null otherwise.
     */
    public function storeResourceFile(ResourceFile $resource) {
        $newResource = $resource->save();
        if($newResource)
            return $resource->id;
        return null;
    }

    /**
     * Gets a resource file belonging to a given resource and game flavor
     *
     * @param $resourceId int the is of the resource instance
     * @param $gameFlavorId int the id of the game flavor
     * @return mixed the @see ResourceFile instance if found, null otherwise.
     */
    public function getFileForResourceForGameFlavor($resourceId, $gameFlavorId) {
        return ResourceFile::where(['resource_id' => $resourceId, 'game_flavor_id' => $gameFlavorId])->first();
    }

    /**
     * @param $gameFlavorId int the id of the game flavor
     * @param $typeId int the resource category type (1 for static resources, 2 for dynamic resources)
     * @return mixed set of results
     */
    public function getResourcesForGameFlavorByResourceType($gameFlavorId, $typeId) {
        $resources = DB::table('resource')
            ->join('resource_category', 'resource.category_id', '=', 'resource_category.id')
            ->join('resource_file', 'resource.id', '=', 'resource_file.resource_id')
            ->where('resource_category.type_id', '=', $typeId)
            ->where('resource_file.game_flavor_id', '=', $gameFlavorId)
            ->orderBy('resource.order_id', 'asc')
            ->orderBy(\DB::raw('-resource.order_id'), 'desc')
            ->get();
        return $resources;
    }

    public function getReourceByNameForCategory($gameResourceFilePath, $categoryId) {
        return Resource::where(['category_id' => $categoryId, 'name' => $gameResourceFilePath])->first();
    }


}
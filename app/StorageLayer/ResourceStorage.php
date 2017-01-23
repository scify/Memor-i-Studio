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

    public function getResourceById($id) {
        return Resource::find($id);
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
    public function getFileForResource($resourceId, $gameFlavorId) {
        return ResourceFile::where(['resource_id' => $resourceId, 'game_flavor_id' => $gameFlavorId])->first();
    }

    public function getResourcesForGameFlavorByResourceType($gameFlavorId, $typeId) {
        $resources = DB::table('resource')
            ->join('resource_category', 'resource.category_id', '=', 'resource_category.id')
            ->join('resource_file', 'resource.id', '=', 'resource_file.resource_id')
            ->where('resource_category.type_id', '=', $typeId)
            ->where('resource_file.game_flavor_id', '=', $gameFlavorId)
            ->get();
        return $resources;
    }
}
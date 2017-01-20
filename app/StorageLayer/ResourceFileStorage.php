<?php
/**
 * Created by IntelliJ IDEA.
 * User: pisaris
 * Date: 12/1/2017
 * Time: 4:38 μμ
 */

namespace App\StorageLayer;

use App\Models\ResourceFile;

class ResourceFileStorage {

    /**
     * Saves a @see ResourceFile instance
     *
     * @param ResourceFile $resource
     * @return null
     */
    public function storeResource(ResourceFile $resource) {
        $newResource = $resource->save();
        if($newResource)
            return $resource->id;
        return null;
    }

    public function getPathForGameFlavorResource($resourceId, $gameFlavorId) {
        return ResourceFile::where(['resource_id' => $resourceId, 'game_flavor_id' => $gameFlavorId])->first();
    }
}
<?php
/**
 * Created by IntelliJ IDEA.
 * User: pisaris
 * Date: 12/1/2017
 * Time: 4:38 μμ
 */

namespace App\StorageLayer;

use App\Models\Resource;

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
}
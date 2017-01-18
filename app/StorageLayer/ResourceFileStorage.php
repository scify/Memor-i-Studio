<?php
/**
 * Created by IntelliJ IDEA.
 * User: pisaris
 * Date: 12/1/2017
 * Time: 4:38 μμ
 */

namespace App\StorageLayer;

use App\Models\GameFlavorResourceFile;

class ResourceFileStorage {

    /**
     * Saves a @see GameFlavorResourceFile instance
     *
     * @param GameFlavorResourceFile $resource
     * @return null
     */
    public function storeResource(GameFlavorResourceFile $resource) {
        $newResource = $resource->save();
        if($newResource)
            return $resource->id;
        return null;
    }
}
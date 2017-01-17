<?php
/**
 * Created by IntelliJ IDEA.
 * User: pisaris
 * Date: 17/1/2017
 * Time: 3:02 μμ
 */

namespace App\StorageLayer;


use App\Models\ResourceTranslation;

class ResourceTranslationStorage {

    public function getTranslationForResource($resourceId, $langId) {
        return ResourceTranslation::where(['resource_id' => $resourceId, 'lang_id' => $langId])->first();
    }

    public function saveResourceTranslation(ResourceTranslation $resourceTranslation) {
        $resourceTranslation->save();
    }
}
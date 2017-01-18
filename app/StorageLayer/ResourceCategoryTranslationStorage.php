<?php
/**
 * Created by IntelliJ IDEA.
 * User: pisaris
 * Date: 18/1/2017
 * Time: 10:31 πμ
 */

namespace App\StorageLayer;


use App\Models\ResourceCategoryTranslation;

class ResourceCategoryTranslationStorage {

    public function getTranslationForResourceCategory($resourceCategoryId, $langId) {
        return ResourceCategoryTranslation::where(['resource_category_id' => $resourceCategoryId, 'lang_id' => $langId])->first();
    }

    public function saveResourceCategoryTranslation(ResourceCategoryTranslation $resourceCategoryTranslation) {
        $resourceCategoryTranslation->save();
    }
}
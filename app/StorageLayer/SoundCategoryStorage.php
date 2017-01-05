<?php
/**
 * Created by IntelliJ IDEA.
 * User: pisaris
 * Date: 5/1/2017
 * Time: 3:40 μμ
 */

namespace App\StorageLayer;


use App\Models\SoundCategory;

class SoundCategoryStorage {

    public function getSoundCategoryByName($name) {
        return SoundCategory::where('category', $name)->firstOrFail();
    }
}
<?php
/**
 * Created by IntelliJ IDEA.
 * User: pisaris
 * Date: 3/1/2017
 * Time: 11:25 πμ
 */

namespace App\StorageLayer;


use App\Models\ImageCategory;

class ImgCategoryStorage {

    public function getImgCategoryByName($name) {
        return ImageCategory::where('category', $name)->firstOrFail();
    }
}
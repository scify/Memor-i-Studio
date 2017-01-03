<?php
/**
 * Created by IntelliJ IDEA.
 * User: pisaris
 * Date: 3/1/2017
 * Time: 11:18 πμ
 */

namespace App\StorageLayer;


use App\Models\Image;

class ImgStorage {
    public function storeImg(Image $image) {
        $newImg = $image->save();
        if($newImg)
            return $image->id;
        return null;
    }
}
<?php
/**
 * Created by IntelliJ IDEA.
 * User: pisaris
 * Date: 3/1/2017
 * Time: 11:15 πμ
 */

namespace App\BusinessLogicLayer\managers;


use App\Models\Image;
use App\StorageLayer\ImgCategoryStorage;
use App\StorageLayer\ImgStorage;
use Illuminate\Http\UploadedFile;

include_once 'functions.php';

class ImgManager {

    private $imgStorage;
    private $imgCategoryStorage;

    public function __construct() {
        // initialize $userStorage
        $this->imgStorage = new ImgStorage();
        $this->imgCategoryStorage = new ImgCategoryStorage();
    }

    public function uploadGameFlavorCoverImg(UploadedFile $coverImg) {
        return $this->createAndStoreNewImage($coverImg, 'game_cover');
    }

    public function uploadCardImg(UploadedFile $coverImg) {
        return $this->createAndStoreNewImage($coverImg, 'card_images');
    }

    public function createAndStoreNewImage(UploadedFile $coverImg, $imgCategory) {
        $filename = 'img' . '_' . milliseconds() . '_' . generateRandomString(6) . '_' . $coverImg->getClientOriginalName();
        $imgCategory = $this->imgCategoryStorage->getImgCategoryByName($imgCategory);
        $imgFields = array(
            'file_path' => $filename,
            'category_id' => $imgCategory->id
        );
        $coverImg->storeAs('images/' . $imgCategory->category, $filename);
        $image = new Image;
        $image->category_id = $imgFields['category_id'];
        $image->file_path = $imgFields['file_path'];
        return $this->imgStorage->storeImg($image);
    }


}
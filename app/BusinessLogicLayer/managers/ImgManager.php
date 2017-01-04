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

class ImgManager {

    private $imgStorage;
    private $imgCategoryStorage;

    public function __construct() {
        // initialize $userStorage
        $this->imgStorage = new ImgStorage();
        $this->imgCategoryStorage = new ImgCategoryStorage();
    }

    public function uploadGameVersionCoverImg(UploadedFile $coverImg) {
        $filename = 'cover_img' . '_' . time() . '.' . $coverImg->getClientOriginalExtension();
        $imgCategory = $this->imgCategoryStorage->getImgCategoryByName('game_cover');
        $coverImgAttrs = array(
            'file_path' => $filename,
            'category_id' => $imgCategory->id
        );
        $coverImg->storeAs('images/' . $imgCategory->category, $filename);
        return $this->createAndStoreNewImage($coverImgAttrs);
    }

    public function createAndStoreNewImage(array $imgFields) {
        $image = new Image;
        $image->category_id = $imgFields['category_id'];
        $image->file_path = $imgFields['file_path'];
        return $this->imgStorage->storeImg($image);
    }
}
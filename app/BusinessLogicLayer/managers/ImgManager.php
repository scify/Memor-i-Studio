<?php
/**
 * Created by IntelliJ IDEA.
 * User: pisaris
 * Date: 3/1/2017
 * Time: 11:15 πμ
 */

namespace App\BusinessLogicLayer\managers;

use Illuminate\Http\UploadedFile;

include_once 'functions.php';

class ImgManager {

    private $resourceManager;

    public function __construct() {
        $this->resourceManager = new ResourceManager();
    }

    public function uploadGameFlavorCoverImg($gameFlavorId, UploadedFile $coverImg) {
        return $this->createAndStoreNewImage($coverImg, 'game_cover', $gameFlavorId);
    }

    public function uploadCardImg($gameFlavorId, UploadedFile $img) {
        return $this->createAndStoreNewImage($img, 'card_images', $gameFlavorId);
    }

    public function createAndStoreNewImage(UploadedFile $img, $imgCategory, $gameFlavorId) {
        return $this->resourceManager->createAndStoreNewResource($img, $imgCategory, $gameFlavorId);
    }


}
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
    private $CARD_IMAGE_CATEGORY = 'img/card_images/';

    public function __construct() {
        $this->resourceManager = new ResourceManager();
    }

    public function uploadGameFlavorCoverImg($gameFlavorId, UploadedFile $coverImg) {
        //TODO: fix
        $soundPath = '';
        return $this->resourceManager->createNewResource($gameFlavorId, $soundPath);
    }

    public function uploadCardImg($gameFlavorId, UploadedFile $img) {
        $imgPath = 'data_packs/additional_pack_' . $gameFlavorId . '/data_pack_' . $gameFlavorId . '/' . $this->CARD_IMAGE_CATEGORY;
        $newResourceId = $this->resourceManager->createNewResource($this->CARD_IMAGE_CATEGORY);
        $this->resourceManager->createAndStoreResourceFile($img, $imgPath, $newResourceId, $gameFlavorId);
        return $newResourceId;
    }

}
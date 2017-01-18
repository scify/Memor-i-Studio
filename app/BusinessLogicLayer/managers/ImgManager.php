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
        //TODO: fix
        $soundPath = '';
        return $this->resourceManager->createAndStoreNewResource($gameFlavorId, $soundPath);
    }

    public function uploadCardImg($gameFlavorId, $cardId, UploadedFile $img) {
        $soundPath = 'data_packs/' . $gameFlavorId . '/img/card_images/';
        return $this->resourceManager->createAndStoreNewCardResource($cardId, $img, $soundPath);
    }

}
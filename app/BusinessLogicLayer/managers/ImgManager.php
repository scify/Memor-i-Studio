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
        return $this->resourceManager->createNewResource($gameFlavorId, $soundPath);
    }

    public function uploadCardImg($gameFlavorId, UploadedFile $img) {
        $soundPath = 'data_packs/' . $gameFlavorId . '/img/card_images/';
        $newResourceId = $this->resourceManager->createNewResource('img/card_images/');
        $this->resourceManager->createAndStoreResourceFile($img, $soundPath, $newResourceId, $gameFlavorId);
        return $newResourceId;
    }

}
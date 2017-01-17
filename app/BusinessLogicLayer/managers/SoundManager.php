<?php
/**
 * Created by IntelliJ IDEA.
 * User: pisaris
 * Date: 5/1/2017
 * Time: 3:21 μμ
 */

namespace App\BusinessLogicLayer\managers;

use Illuminate\Http\UploadedFile;
include_once 'functions.php';

class SoundManager {

    private $resourceManager;

    public function __construct() {
        $this->resourceManager = new \ResourceManager();
    }

    public function uploadCardSound($gameFlavorId, UploadedFile $sound) {
        return $this->createAndStoreNewSound($sound, 'card_sounds', $gameFlavorId);
    }

    public function createAndStoreNewSound(UploadedFile $sound, $soundCategory, $gameFlavorId) {
        return $this->resourceManager->createAndStoreNewResource($sound, $soundCategory, $gameFlavorId);
    }

}
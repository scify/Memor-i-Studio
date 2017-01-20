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
        $this->resourceManager = new ResourceManager();
    }

    public function uploadCardSound($gameFlavorId, UploadedFile $sound) {
        $soundPath = 'data_packs/' . $gameFlavorId . '/audios/card_sounds/';
        $newResourceId = $this->resourceManager->createNewResource('audios/card_sounds/');
        $this->resourceManager->createAndStoreResourceFile($sound, $soundPath, $newResourceId, $gameFlavorId);
        return $newResourceId;
    }

}
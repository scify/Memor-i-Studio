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
    private $CARD_SOUND_CATEGORY = 'audios/card_sounds/';

    public function __construct() {
        $this->resourceManager = new ResourceManager();
    }

    public function uploadCardSound($gameFlavorId, UploadedFile $sound) {
        $soundPath = 'data_packs/' . $gameFlavorId . '/' . $this->CARD_SOUND_CATEGORY;
        $newResourceId = $this->resourceManager->createNewResource($this->CARD_SOUND_CATEGORY);
        $this->resourceManager->createAndStoreResourceFile($sound, $soundPath, $newResourceId, $gameFlavorId);
        return $newResourceId;
    }

}
<?php
/**
 * Created by IntelliJ IDEA.
 * User: pisaris
 * Date: 5/1/2017
 * Time: 3:21 μμ
 */

namespace App\BusinessLogicLayer\managers;

use App\Models\Resource;
use Illuminate\Http\UploadedFile;

/**
 * Class SoundManager handles the sound files of the dynamic resources (eg the card sounds)
 * @package App\BusinessLogicLayer\managers
 */
class SoundManager {

    private $resourceManager;
    private $CARD_SOUND_CATEGORY = 'audios/card_sounds/';

    public function __construct() {
        $this->resourceManager = new ResourceManager();
    }

    /**
     * Creates a new @see Resource for the card image and stores the file.
     *
     * @param $gameFlavorId int the id of the game flavor
     * @param UploadedFile $sound the sound file uploaded
     * @return int the id of the resource created
     */
    public function uploadCardSound($gameFlavorId, UploadedFile $sound) {
        $soundPath = 'data_packs/additional_pack_' . $gameFlavorId . '/data_pack_' . $gameFlavorId . '/' . $this->CARD_SOUND_CATEGORY;
        $newResourceId = $this->resourceManager->createNewResource($this->CARD_SOUND_CATEGORY);
        $this->resourceManager->createAndStoreResourceFile($sound, $soundPath, $newResourceId, $gameFlavorId);
        return $newResourceId;
    }

}
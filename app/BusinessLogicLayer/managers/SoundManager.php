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
    private $CARD_SOUND_PATH = 'audios/card_sounds/';
    // The equivalence set description sound must be stored in the directory card_description_sounds
    // because in the game application it is refer to as "card description sound" and not "equivalence set description sound"
    // So it it expected to appear in this specific folder.
    private $EQUIVALENCE_SET_DESCRIPTION_SOUND_PATH = 'audios/card_description_sounds/';

    public function __construct(ResourceManager $resourceManager) {
        $this->resourceManager = $resourceManager;
    }

    /**
     * Creates a new @see Resource for the card image and stores the file.
     *
     * @param $gameFlavorId int the id of the game flavor
     * @param UploadedFile $sound the sound file uploaded
     * @return int the id of the resource created
     */
    public function uploadCardSound($gameFlavorId, UploadedFile $sound) {
        return $this->createNewResourceAndUploadFile($gameFlavorId, $sound, $this->CARD_SOUND_PATH);
    }

    /**
     * Creates a new @see Resource for the equivalence set description sound and stores the file.
     *
     * @param $gameFlavorId int the id of the game flavor
     * @param UploadedFile $sound the sound file uploaded
     * @return int the id of the resource created
     */
    public function uploadEquivalenceSetDescriptionSound($gameFlavorId, UploadedFile $sound) {
        return $this->createNewResourceAndUploadFile($gameFlavorId, $sound, $this->EQUIVALENCE_SET_DESCRIPTION_SOUND_PATH);
    }

    private function createNewResourceAndUploadFile($gameFlavorId, UploadedFile $sound, $pathToStore) {
        $soundPath = 'data_packs/additional_pack_' . $gameFlavorId . '/data/' . $pathToStore;
        $newResourceId = $this->resourceManager->createNewResource($pathToStore);
        $this->resourceManager->createAndStoreResourceFile($sound, $soundPath, $newResourceId, $gameFlavorId);
        return $newResourceId;
    }

}

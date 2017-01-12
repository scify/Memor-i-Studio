<?php
/**
 * Created by IntelliJ IDEA.
 * User: pisaris
 * Date: 5/1/2017
 * Time: 3:21 μμ
 */

namespace App\BusinessLogicLayer\managers;


use App\Models\Sound;
use App\StorageLayer\SoundCategoryStorage;
use App\StorageLayer\SoundStorage;
use Illuminate\Http\UploadedFile;
include_once 'functions.php';

class SoundManager {

    private $soundStorage;
    private $soundCategoryStorage;

    public function __construct() {
        $this->soundStorage = new SoundStorage();
        $this->soundCategoryStorage = new SoundCategoryStorage();
    }

    public function uploadCardSound($gameFlavorId, UploadedFile $sound) {
        return $this->createAndStoreNewSound($sound, 'card_sounds', $gameFlavorId);
    }

    public function createAndStoreNewSound(UploadedFile $sound, $soundCategory, $gameFlavorId) {
        $filename = 'sound' . '_' . milliseconds() . '_' . generateRandomString(6) . '_' . $sound->getClientOriginalName();
        $soundCategory = $this->soundCategoryStorage->getSoundCategoryByName($soundCategory);

        $sound->storeAs('packs/' . $gameFlavorId . '/' . 'audios/' . $soundCategory->category, $filename);
        $soundObj = new Sound();
        $soundObj->category_id = $soundCategory->id;
        $soundObj->file_path = $filename;

        return $this->soundStorage->storeSound($soundObj);
    }

}
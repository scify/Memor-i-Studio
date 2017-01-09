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

class SoundManager {

    private $soundStorage;
    private $soundCategoryStorage;

    public function __construct() {
        $this->soundStorage = new SoundStorage();
        $this->soundCategoryStorage = new SoundCategoryStorage();
    }

    public function uploadCardSound(UploadedFile $sound) {
        return $this->createAndStoreNewSound($sound, 'card_sounds');
    }

    public function createAndStoreNewSound(UploadedFile $sound, $soundCategory) {
        $filename = 'sound' . '_' . $this->milliseconds() . '_' . $sound->getClientOriginalName();
        $soundCategory = $this->soundCategoryStorage->getSoundCategoryByName($soundCategory);

        $sound->storeAs('sounds/' . $soundCategory->category, $filename);
        $soundObj = new Sound();
        $soundObj->category_id = $soundCategory->id;
        $soundObj->file_path = $filename;

        return $this->soundStorage->storeSound($soundObj);
    }

    private function milliseconds() {
        return round(microtime(true) * 1000);
    }
}
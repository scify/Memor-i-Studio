<?php
/**
 * Created by IntelliJ IDEA.
 * User: pisaris
 * Date: 5/1/2017
 * Time: 3:44 μμ
 */

namespace App\StorageLayer;


use App\Models\Sound;

class SoundStorage {

    public function storeSound(Sound $sound) {
        $newSound = $sound->save();
        if($newSound)
            return $sound->id;
        return null;
    }
}
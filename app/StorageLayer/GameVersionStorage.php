<?php
/**
 * Created by IntelliJ IDEA.
 * User: pisaris
 * Date: 3/1/2017
 * Time: 10:46 πμ
 */

namespace App\StorageLayer;


use App\Models\GameVersion;

class GameVersionStorage {

    /**
     * Stores @see GameVersion object to DB
     * @param GameVersion $gameVersion the object to be stored
     * @return GameVersion the newly created game version
     */
    public function storeGameVersion(GameVersion $gameVersion) {
        $gameVersion->save();
        return $gameVersion;
    }

    public function getPublishedGameVersions() {
        return GameVersion::where('published', true)->sortByDesc("created_at")->get();
    }

    public function getAllGameVersions() {
        return GameVersion::all()->sortByDesc("created_at");
    }
}
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

    public function getGameVersionsByPublishedState($state) {
        return GameVersion::where('published', $state)->get()->sortByDesc("created_at");
    }

    public function getAllGameVersions() {
        return GameVersion::all()->sortByDesc("created_at");
    }

    public function getGameVersionById($id) {
        return GameVersion::find($id);
    }

    public function getGameVersionsByPublishedStateByUser($state, $userId) {
        return GameVersion::where([
            ['published', '=', $state],
            ['creator_id', '=', $userId],
        ])->get()->sortByDesc("created_at");


    }

    public function getGameVersionByIdCreatedByUser($id, $userId) {
        return GameVersion::where([
            ['id', '=', $id],
            ['creator_id', '=', $userId],
        ])->get()->first();
    }

    public function deleteGameVersion(GameVersion $gameVersion) {
        $gameVersion->delete();
    }
}
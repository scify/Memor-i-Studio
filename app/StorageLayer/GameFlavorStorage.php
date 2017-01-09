<?php
/**
 * Created by IntelliJ IDEA.
 * User: pisaris
 * Date: 3/1/2017
 * Time: 10:46 πμ
 */

namespace App\StorageLayer;


use App\Models\GameFlavor;

class GameFlavorStorage {

    /**
     * Stores @see GameFlavor object to DB
     * @param GameFlavor $gameVersion the object to be stored
     * @return GameFlavor the newly created game version
     */
    public function storeGameFlavor(GameFlavor $gameVersion) {
        $gameVersion->save();
        return $gameVersion;
    }

    public function getGameFlavorsByPublishedState($state) {
        return GameFlavor::where('published', $state)->get()->sortByDesc("created_at");
    }

    public function getAllGameFlavors() {
        return GameFlavor::all()->sortByDesc("created_at");
    }

    public function getGameFlavorById($id) {
        return GameFlavor::find($id);
    }

    public function getGameFlavorsByPublishedStateByUser($state, $userId) {
        return GameFlavor::where([
            ['published', '=', $state],
            ['creator_id', '=', $userId],
        ])->get()->sortByDesc("created_at");

    }

    public function getGameFlavorByIdCreatedByUser($id, $userId) {
        return GameFlavor::where([
            ['id', '=', $id],
            ['creator_id', '=', $userId],
        ])->get()->first();
    }

    public function deleteGameFlavor(GameFlavor $gameVersion) {
        $gameVersion->delete();
    }
}
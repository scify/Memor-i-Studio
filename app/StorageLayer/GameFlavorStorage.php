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
     * @param GameFlavor $gameFlavor the object to be stored
     * @return GameFlavor the newly created game version
     */
    public function storeGameFlavor(GameFlavor $gameFlavor) {
        $gameFlavor->save();
        return $gameFlavor;
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

    public function getGameFlavorByGameIdentifier($gameIdentifier) {
        return GameFlavor::where([
            ['game_identifier', $gameIdentifier]
        ])->get()->first();

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

    public function deleteGameFlavor(GameFlavor $gameFlavor) {
        $gameFlavor->delete();
    }

    public function gatGameFlavorsBySubmittedState($submittedState) {
        return GameFlavor::where([
            ['submitted_for_approval', '=', $submittedState],
        ])->get()->sortByDesc("created_at");
    }
}
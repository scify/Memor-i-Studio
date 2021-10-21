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

    protected $default_relationships = ['coverImg', 'coverImg.file', 'language', 'creator', 'gameVersion'];
    /**
     * Stores @param GameFlavor $gameFlavor the object to be stored
     * @return GameFlavor the newly created game version
     * @see GameFlavor object to DB
     */
    public function storeGameFlavor(GameFlavor $gameFlavor) {
        $gameFlavor->save();
        return $gameFlavor;
    }

    public function getGameFlavorsByPublishedState($state) {
        return GameFlavor::where('published', $state)->with($this->default_relationships)->orderBy('created_at', 'desc')->get();
    }

    public function getAllGameFlavors() {
        return GameFlavor::with($this->default_relationships)->orderBy('created_at', 'desc')->get();
    }

    public function getGameFlavorById($id) {
        return GameFlavor::find($id);
    }

    public function getGameFlavorByGameIdentifier($gameIdentifier) {
        return GameFlavor::where([
            ['game_identifier', $gameIdentifier]
        ])->with($this->default_relationships)->first();

    }

    public function getGameFlavorsByPublishedStateByUser($state, $userId) {
        return GameFlavor::where([
            ['published', '=', $state],
            ['creator_id', '=', $userId],
        ])->with($this->default_relationships)->orderBy('created_at', 'desc')->get();

    }

    public function getGameFlavorByIdCreatedByUser($id, $userId) {
        return GameFlavor::where([
            ['id', '=', $id],
            ['creator_id', '=', $userId],
        ])->with($this->default_relationships)->first();
    }

    public function deleteGameFlavor(GameFlavor $gameFlavor) {
        $gameFlavor->delete();
    }

    public function gatGameFlavorsBySubmittedState($submittedState) {
        return GameFlavor::where([
            ['submitted_for_approval', '=', $submittedState],
        ])->with($this->default_relationships)->orderBy('created_at', 'desc')->get();
    }
}

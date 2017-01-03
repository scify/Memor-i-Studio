<?php
/**
 * Created by IntelliJ IDEA.
 * User: pisaris
 * Date: 3/1/2017
 * Time: 10:46 πμ
 */

namespace App\BusinessLogicLayer\managers;


use App\Models\GameVersion;
use App\StorageLayer\GameVersionStorage;
use Illuminate\Support\Facades\Auth;

class GameVersionManager {

    private $gameVersionStorage;

    public function __construct() {
        // initialize $userStorage
        $this->gameVersionStorage = new GameVersionStorage();
    }

    public function createGameVersion(array $gameVersionFields) {
        $gameVersion = new GameVersion;
        $gameVersion->name = $gameVersionFields['name'];
        $gameVersion->description = $gameVersionFields['description'];
        $gameVersion->lang_id = $gameVersionFields['lang_id'];
        $gameVersion->creator_id = $gameVersionFields['creator_id'];
        $gameVersion->cover_img_id = $gameVersionFields['cover_img_id'];

        return $this->gameVersionStorage->storeGameVersion($gameVersion);
    }

    public function getGameVersions() {
        $user = Auth::user();
        if($user->isAdmin()) {
            return $this->gameVersionStorage->getAllGameVersions();
        }
        return $this->gameVersionStorage->getPublishedGameVersions();
    }
}
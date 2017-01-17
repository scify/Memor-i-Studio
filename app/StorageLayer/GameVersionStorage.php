<?php
/**
 * Created by IntelliJ IDEA.
 * User: pisaris
 * Date: 13/1/2017
 * Time: 11:29 πμ
 */

namespace App\StorageLayer;


use App\Models\GameVersion;
use Illuminate\Database\Eloquent\Collection;

class GameVersionStorage {

    /**
     * Stores @see GameFlavor object to DB
     * @param GameVersion $gameVersion the object to be stored
     * @return GameVersion the newly created game version
     */
    public function storeGameVersion(GameVersion $gameVersion) {
        $gameVersion->save();
        return $gameVersion;
    }

    /**
     * Gets all the @see GameVersion instances from the DB
     *
     * @return Collection with all the instances
     */
    public function getAllGameVersions() {
        return GameVersion::all()->sortByDesc("created_at");
    }

    /**
     * Fetches a particular @see GameVersion instance (or null)
     *
     * @param $id
     * @return mixed the instance meeting the criteria, or null
     */
    public function getGameVersionById($id) {
        return GameVersion::find($id);
    }

}
<?php
/**
 * Created by IntelliJ IDEA.
 * User: pisaris
 * Date: 17/1/2017
 * Time: 12:13 μμ
 */

namespace App\StorageLayer;


use App\Models\GameVersionLanguage;

class GameVersionLanguageStorage {

    public function getGameVersionLanguages($id) {
        return GameVersionLanguage::where(['game_version_id' => $id])->get();
    }

    public function getLanguageForGameVersion($gameVersionId, $langId) {
        return GameVersionLanguage::where(['game_version_id' => $gameVersionId, 'lang_id' => $langId])->first();
    }

    public function storeGameVersionLanguage($gameVersionLanguage) {
        $gameVersionLanguage->save();
    }
}
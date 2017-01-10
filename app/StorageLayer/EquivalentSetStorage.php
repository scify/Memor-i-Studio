<?php
/**
 * Created by IntelliJ IDEA.
 * User: pisaris
 * Date: 9/1/2017
 * Time: 3:08 μμ
 */

namespace App\StorageLayer;


use App\Models\EquivalenceSet;

class EquivalentSetStorage {

    public function getEquivalenceSetsForGameFlavor($gameFlavorId) {
        return EquivalenceSet::where([
            ['flavor_id', '=', $gameFlavorId]
        ])->get()->sortByDesc("created_at");
    }

    public function saveEquivalenceSet(EquivalenceSet $equivalenceSet) {
        $equivalenceSet->save();
        return $equivalenceSet;
    }

    public function deleteSet($id) {
        EquivalenceSet::find($id)->delete();
    }
}
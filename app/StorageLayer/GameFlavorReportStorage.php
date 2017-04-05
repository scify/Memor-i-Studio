<?php

namespace App\StorageLayer;

use App\Models\GameFlavorReport;

class GameFlavorReportStorage {

    public function storeGameFlavorReport(GameFlavorReport $gameFlavorReport) {
        $gameFlavorReport->save();
        return $gameFlavorReport;
    }

    public function getAllGameFlavorReports() {
        return GameFlavorReport::all()->sortByDesc("created_at");
    }

    public function getGameFlavorreportById($id) {
        return GameFlavorReport::find($id);
    }

    public function deleteGameFlavorReport(GameFlavorReport $gameFlavor) {
        $gameFlavor->delete();
    }

}
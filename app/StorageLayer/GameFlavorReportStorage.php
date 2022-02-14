<?php

namespace App\StorageLayer;

use App\Models\GameFlavorReport;
use Illuminate\Support\Collection;

class GameFlavorReportStorage {

    public function storeGameFlavorReport(GameFlavorReport $gameFlavorReport): GameFlavorReport {
        $gameFlavorReport->save();
        return $gameFlavorReport;
    }

    public function getAllGameFlavorReports(): Collection {
        return GameFlavorReport::all()->sortByDesc("created_at");
    }

}

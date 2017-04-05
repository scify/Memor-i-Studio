<?php

namespace App\BusinessLogicLayer\managers;
use App\Models\GameFlavorReport;
use App\StorageLayer\GameFlavorReportStorage;
use Illuminate\Support\Facades\Auth;

class GameFlavorReportManager {

    private $gameFlavorReportStorage;

    public function __construct() {
        $this->gameFlavorReportStorage = new GameFlavorReportStorage();
    }

    public function createGameFlavorReport(array $inputFields) {

        $gameFlavorReport = new GameFlavorReport;
        $gameFlavorReport->user_id = Auth::user()->id;
        $gameFlavorReport = $this->assignValuesToGameFlavorReport($gameFlavorReport, $inputFields);
        $gameFlavorReport->game_flavor_id = $inputFields['game_flavor_id'];

        $this->gameFlavorReportStorage->storeGameFlavorReport($gameFlavorReport);
    }

    private function assignValuesToGameFlavorReport(GameFlavorReport $gameFlavorReport, $gameFlavorReportFields) {
        $gameFlavorReport->game_flavor_id = $gameFlavorReportFields['game_flavor_id'];
        $gameFlavorReport->user_comment = $gameFlavorReportFields['user_comment'];
        return $gameFlavorReport;
    }

    public function getAllGameFlavorReports() {
        return $this->gameFlavorReportStorage->getAllGameFlavorReports();
    }
}
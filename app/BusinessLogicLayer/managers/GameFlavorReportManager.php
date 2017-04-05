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
        $inputFields['user_id'] = Auth::user()->id;
        $gameFlavorReport = new GameFlavorReport;
        $gameFlavorReport = $this->assignValuesToGameFlavorReport($gameFlavorReport, $inputFields);
        $gameFlavorReport->game_version_id = $inputFields['game_version_id'];
        $gameFlavorReport->user_id = $inputFields['creator_id'];
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
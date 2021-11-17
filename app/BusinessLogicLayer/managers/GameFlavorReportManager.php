<?php

namespace App\BusinessLogicLayer\managers;
use App\Models\GameFlavorReport;
use App\StorageLayer\GameFlavorReportStorage;
use Illuminate\Support\Facades\Auth;

class GameFlavorReportManager {

    private $gameFlavorReportStorage;

    public function __construct(GameFlavorReportStorage $gameFlavorReportStorage) {
        $this->gameFlavorReportStorage = $gameFlavorReportStorage;
    }

    public function createGameFlavorReport(array $inputFields) {

        $gameFlavorReport = new GameFlavorReport;
        $loggedInUser = Auth::user();
        if($loggedInUser != null)
            $gameFlavorReport->user_id = Auth::user()->id;
        $gameFlavorReport = $this->assignValuesToGameFlavorReport($gameFlavorReport, $inputFields);
        $gameFlavorReport->game_flavor_id = $inputFields['game_flavor_id'];

        $this->gameFlavorReportStorage->storeGameFlavorReport($gameFlavorReport);
    }

    private function assignValuesToGameFlavorReport(GameFlavorReport $gameFlavorReport, $gameFlavorReportFields) {
        $gameFlavorReport->game_flavor_id = $gameFlavorReportFields['game_flavor_id'];
        $gameFlavorReport->user_comment = $gameFlavorReportFields['user_comment'];
        if(isset($gameFlavorReportFields['user_name']))
            $gameFlavorReport->user_comment = $gameFlavorReportFields['user_name'];
        if(isset($gameFlavorReportFields['user_email']))
            $gameFlavorReport->user_comment = $gameFlavorReportFields['user_email'];
        return $gameFlavorReport;
    }

    public function getAllGameFlavorReports() {
        return $this->gameFlavorReportStorage->getAllGameFlavorReports();
    }
}

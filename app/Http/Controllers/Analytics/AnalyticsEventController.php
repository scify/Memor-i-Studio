<?php

namespace App\Http\Controllers\Analytics;

use App\BusinessLogicLayer\managers\SHAPES\ShapesIntegrationManager;
use App\Http\Controllers\Controller;
use App\StorageLayer\Analytics\AnalyticsEventStorage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AnalyticsEventController extends Controller {
    protected $analyticsEventStorage;
    private $shapesIntegrationManager;

    public function __construct(AnalyticsEventStorage    $analyticsEventStorage,
                                ShapesIntegrationManager $shapesIntegrationManager) {
        $this->analyticsEventStorage = $analyticsEventStorage;
        $this->shapesIntegrationManager = $shapesIntegrationManager;
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required',
            'source' => 'required'
        ]);
        Log::info("integration enabled: " . ShapesIntegrationManager::isEnabled() && isset($request->game_name));
        if (ShapesIntegrationManager::isEnabled() && isset($request->game_name)) {
            $game_duration_seconds = null;
            $num_of_errors = null;
            if (isset($request->game_duration_seconds))
                $game_duration_seconds = $request->game_duration_seconds;
            if (isset($request->num_of_errors))
                $num_of_errors = $request->num_of_errors;

            $this->shapesIntegrationManager->sendDesktopUsageDataToDatalakeAPI(
                $request->source,
                $request->token,
                $request->name,
                $request->game_name,
                $request->game_level,
                $game_duration_seconds,
                $num_of_errors
            );
        }
        return $this->analyticsEventStorage->create([
            'name' => $request->name,
            'source' => $request->source,
            'payload' => json_encode($request->all())
        ]);
    }
}

<?php

namespace App\Http\Controllers\Analytics;

use App\BusinessLogicLayer\managers\SHAPES\ShapesIntegrationManager;
use App\Http\Controllers\Controller;
use App\StorageLayer\Analytics\AnalyticsEventRepository;
use Illuminate\Http\Request;

class AnalyticsEventController extends Controller {
    protected $analyticsEventRepository;
    private $shapesIntegrationManager;

    public function __construct(AnalyticsEventRepository $analyticsEventRepository,
                                ShapesIntegrationManager $shapesIntegrationManager) {
        $this->analyticsEventRepository = $analyticsEventRepository;
        $this->shapesIntegrationManager = $shapesIntegrationManager;
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required',
            'source' => 'required'
        ]);
        $response = json_encode([]);
        if (ShapesIntegrationManager::isEnabled() && isset($request->token)) {
            $game_duration_seconds = null;
            $num_of_errors = null;
            if (isset($request->game_duration_seconds))
                $game_duration_seconds = $request->game_duration_seconds;
            if (isset($request->num_of_errors))
                $num_of_errors = $request->num_of_errors;

            $response = $this->shapesIntegrationManager->sendDesktopUsageDataToDatalakeAPI(
                $request->source,
                $request->token,
                $request->name,
                $request->game_name,
                $request->game_level,
                $game_duration_seconds,
                $num_of_errors
            );
        }
        return $this->analyticsEventRepository->create([
            'name' => $request->name,
            'source' => $request->source,
            'payload' => json_encode($request->all()),
            'response' => $response
        ]);
    }

    public function storeICSeeEvent(Request $request) {
        $request->validate([
            'name' => 'required',
            'source' => 'required'
        ]);
        $response = json_encode([]);
        if (ShapesIntegrationManager::isEnabled() && isset($request->token)) {
            $response = $this->shapesIntegrationManager->sendICSeeUsageDataToDatalakeAPI(
                $request->action,
                $request->value,
                $request->token
            );
        }
        return $this->analyticsEventRepository->create([
            'name' => $request->name,
            'source' => $request->source,
            'payload' => json_encode($request->all()),
            'response' => $response
        ]);
    }

    public function storeNewsumEvent(Request $request) {
        $request->validate([
            'name' => 'required',
            'source' => 'required'
        ]);
        $response = json_encode([]);
        if (ShapesIntegrationManager::isEnabled() && isset($request->token)) {
            $response = $this->shapesIntegrationManager->sendNewsumUsageDataToDatalakeAPI(
                $request->action,
                $request->value,
                $request->token
            );
        }
        return $this->analyticsEventRepository->create([
            'name' => $request->name,
            'source' => $request->source,
            'payload' => json_encode($request->all()),
            'response' => $response
        ]);
    }
}

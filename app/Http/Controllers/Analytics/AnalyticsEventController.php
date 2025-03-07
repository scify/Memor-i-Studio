<?php

namespace App\Http\Controllers\Analytics;

use App\Http\Controllers\Controller;
use App\StorageLayer\Analytics\AnalyticsEventRepository;
use Illuminate\Http\Request;

class AnalyticsEventController extends Controller {
    protected $analyticsEventRepository;

    public function __construct(AnalyticsEventRepository $analyticsEventRepository) {
        $this->analyticsEventRepository = $analyticsEventRepository;
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required',
            'source' => 'required'
        ]);
        $response = json_encode([]);
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
        return $this->analyticsEventRepository->create([
            'name' => $request->name,
            'source' => $request->source,
            'payload' => json_encode($request->all()),
            'response' => $response
        ]);
    }
}

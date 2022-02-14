<?php

namespace App\Http\Controllers\Analytics;

use App\Http\Controllers\Controller;
use App\StorageLayer\Analytics\AnalyticsEventStorage;
use Illuminate\Http\Request;

class AnalyticsEventController extends Controller {
    protected $analyticsEventStorage;

    public function __construct(AnalyticsEventStorage $analyticsEventStorage) {
        $this->analyticsEventStorage = $analyticsEventStorage;
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required',
            'source' => 'required'
        ]);
        return $this->analyticsEventStorage->create([
            'name' => $request->name,
            'source' => $request->source,
            'payload' => json_encode($request->all())
        ]);
    }
}

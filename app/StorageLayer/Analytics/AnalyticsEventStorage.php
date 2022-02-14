<?php

namespace App\StorageLayer\Analytics;

use App\Models\Analytics\AnalyticsEvent;

class AnalyticsEventStorage {

    public function create(array $data) {
        return AnalyticsEvent::create([
            'name' => $data['name'],
            'source' => $data['source'],
            'payload' => $data['payload']
        ]);
    }
}

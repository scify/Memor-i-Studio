<?php

namespace App\StorageLayer\Analytics;

use App\Models\Analytics\AnalyticsEvent;
use App\StorageLayer\Repository;

class AnalyticsEventRepository extends Repository {

    function getModelClassName(): string {
       return AnalyticsEvent::class;
    }
}

<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;

class TrustProxies extends Middleware {
    /**
     * Only the proxies in config/proxies.php may set the client address, so the login throttle sees the
     * real client and nobody else can choose an address through X-Forwarded-For.
     *
     * @return array<int, string>
     */
    protected function proxies() {
        return config('proxies.trusted');
    }
}

<?php

namespace App\Support;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\IpUtils;

class RateLimitAddress {
    /**
     * The address part of a rate limit key. One IPv6 host owns a whole /64 network, so IPv6 addresses count per
     * /64; IPv4 addresses stay as they are.
     */
    public static function of(Request $request): string {
        $address = $request->ip();

        return $address ? IpUtils::anonymize($address, 0, 8) : 'unknown';
    }
}

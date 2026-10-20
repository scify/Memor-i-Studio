<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\IpUtils;

class TrustProxies extends Middleware {
    /**
     * Only the address and the scheme come from the proxy; X-Forwarded-Host and -Prefix could change generated links.
     *
     * @var int
     */
    protected $headers = Request::HEADER_X_FORWARDED_FOR | Request::HEADER_X_FORWARDED_PROTO;

    /**
     * Behind Cloudflare, X-Forwarded-For can carry values that a client or a Worker chose, so the client address
     * comes from CF-Connecting-IP, which only Cloudflare sets. Other senders cannot choose an address at all.
     */
    public function handle(Request $request, Closure $next) {
        if (IpUtils::checkIp((string) $request->server->get('REMOTE_ADDR'), $this->proxies())) {
            $clientAddress = $request->headers->get('CF-Connecting-IP');
            if (filter_var($clientAddress, FILTER_VALIDATE_IP)) {
                $request->headers->set('X-Forwarded-For', $clientAddress);
            } else {
                $request->headers->remove('X-Forwarded-For');
            }
        }

        return parent::handle($request, $next);
    }

    /**
     * @return array<int, string>
     */
    protected function proxies() {
        return config('proxies.trusted');
    }
}

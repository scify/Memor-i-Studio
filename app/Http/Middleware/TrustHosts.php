<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustHosts as Middleware;

class TrustHosts extends Middleware {
    /**
     * Requests for other hosts are refused, so a forged Host header cannot change generated links
     * (for example in password reset emails). Laravel skips this check in the local environment.
     *
     * @return array<int, string>
     */
    public function hosts(): array {
        return [$this->allSubdomainsOfApplicationUrl()];
    }
}

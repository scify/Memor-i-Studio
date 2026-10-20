<?php

namespace Tests\Unit;

use App\Http\Kernel;
use App\Http\Middleware\TrustProxies;
use Illuminate\Http\Request;
use Tests\TestCase;

/**
 * Production runs behind Cloudflare: the client address comes from X-Forwarded-For, but only from Cloudflare.
 */
class TrustProxiesTest extends TestCase {
    public function test_every_request_passes_trust_proxies(): void {
        $this->assertTrue(app(Kernel::class)->hasMiddleware(TrustProxies::class));
    }

    public function test_a_request_through_cloudflare_reports_the_client_address(): void {
        $this->assertSame('203.0.113.7', $this->clientIpBehind('173.245.48.10'));
    }

    public function test_another_proxy_cannot_choose_the_client_address(): void {
        $this->assertSame('198.51.100.20', $this->clientIpBehind('198.51.100.20'));
    }

    private function clientIpBehind(string $remoteAddress): string {
        $request = Request::create('/', 'GET', server: ['REMOTE_ADDR' => $remoteAddress, 'HTTP_X_FORWARDED_FOR' => '203.0.113.7']);

        return app(TrustProxies::class)->handle($request, fn (Request $request) => response($request->ip()))->getContent();
    }
}

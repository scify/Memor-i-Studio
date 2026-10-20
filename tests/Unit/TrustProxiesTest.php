<?php

namespace Tests\Unit;

use App\Http\Kernel;
use App\Http\Middleware\TrustProxies;
use Illuminate\Http\Request;
use Tests\TestCase;

/**
 * Production runs behind Cloudflare. The client address comes from CF-Connecting-IP, which Cloudflare sets and
 * a client or a Worker cannot; X-Forwarded-For can carry any value that a client or a Worker chose.
 */
class TrustProxiesTest extends TestCase {
    private const CLOUDFLARE = '173.245.48.10';

    public function test_every_request_passes_trust_proxies(): void {
        $this->assertTrue(app(Kernel::class)->hasMiddleware(TrustProxies::class));
    }

    public function test_a_request_through_cloudflare_reports_the_connecting_client(): void {
        $request = $this->handle(self::CLOUDFLARE, ['HTTP_CF_CONNECTING_IP' => '203.0.113.7', 'HTTP_X_FORWARDED_FOR' => '6.6.6.6, 2a06:98c0:3600::103']);

        $this->assertSame('203.0.113.7', $request->ip());
    }

    public function test_an_ipv4_mapped_cloudflare_peer_is_recognised(): void {
        $request = $this->handle('::ffff:' . self::CLOUDFLARE, ['HTTP_CF_CONNECTING_IP' => '203.0.113.7']);

        $this->assertSame('203.0.113.7', $request->ip());
    }

    public function test_a_cloudflare_request_without_the_cloudflare_header_cannot_choose_its_address(): void {
        $request = $this->handle(self::CLOUDFLARE, ['HTTP_X_FORWARDED_FOR' => '6.6.6.6']);

        $this->assertSame(self::CLOUDFLARE, $request->ip());
    }

    public function test_another_sender_cannot_choose_its_address(): void {
        $request = $this->handle('198.51.100.20', ['HTTP_CF_CONNECTING_IP' => '6.6.6.6', 'HTTP_X_FORWARDED_FOR' => '6.6.6.6']);

        $this->assertSame('198.51.100.20', $request->ip());
    }

    public function test_only_the_forwarded_address_and_scheme_are_trusted(): void {
        $request = $this->handle(self::CLOUDFLARE, ['HTTP_CF_CONNECTING_IP' => '203.0.113.7', 'HTTP_X_FORWARDED_PROTO' => 'https', 'HTTP_X_FORWARDED_PREFIX' => '/evil', 'HTTP_X_FORWARDED_HOST' => 'evil.example.com']);

        $this->assertTrue($request->isSecure());
        $this->assertSame('', $request->getBaseUrl());
        $this->assertSame('localhost', $request->getHost());
    }

    private function handle(string $remoteAddress, array $server): Request {
        $request = Request::create('http://localhost/', 'GET', server: ['REMOTE_ADDR' => $remoteAddress] + $server);
        app(TrustProxies::class)->handle($request, fn () => response(''));

        return $request;
    }
}

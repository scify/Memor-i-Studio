<?php

namespace Tests\Unit;

use App\Http\Kernel;
use App\Http\Middleware\TrustHosts;
use Tests\TestCase;

/**
 * Laravel switches TrustHosts off in tests and in the local environment, so check its setup directly.
 */
class TrustHostsTest extends TestCase {
    public function test_every_request_passes_trust_hosts(): void {
        $this->assertTrue(app(Kernel::class)->hasMiddleware(TrustHosts::class));
    }

    public function test_only_the_application_host_and_its_subdomains_are_trusted(): void {
        config(['app.url' => 'https://memoristudio.scify.org']);
        $patterns = app(TrustHosts::class)->hosts();

        $trusted = fn (string $host) => collect($patterns)->contains(fn ($pattern) => preg_match('{' . $pattern . '}i', $host) === 1);

        $this->assertTrue($trusted('memoristudio.scify.org'));
        $this->assertTrue($trusted('www.memoristudio.scify.org'));
        $this->assertFalse($trusted('evil.example.com'));
        $this->assertFalse($trusted('memoristudio.scify.org.evil.example.com'));
    }
}

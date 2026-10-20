<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Feature\Concerns\CreatesGameData;
use Tests\TestCase;

class RouteHardeningTest extends TestCase {
    use CreatesGameData;

    use RefreshDatabase;

    protected bool $seed = true;

    public static function debugRoutes(): array {
        return [
            'email template preview' => ['/testEmail'],
            'sentry test exception' => ['/debug-sentry'],
            'file write from a query string' => ['/storeUserAction?actions=1'],
            'admin email test' => ['/administration/test-email/someone@example.com'],
        ];
    }

    #[DataProvider('debugRoutes')]
    public function test_debug_routes_are_gone(string $url): void {
        $this->actingAs($this->createUser(self::ADMIN_ROLE_ID))->get($url)->assertNotFound();
    }
}

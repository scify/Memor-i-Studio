<?php

namespace Tests\Unit;

use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use ReflectionMethod;
use Tests\TestCase;

/**
 * Laravel skips the CSRF check in tests, so check the middleware's exception list directly:
 * only the desktop game API (api/*) may skip it.
 */
class CsrfExceptionsTest extends TestCase {
    public function test_only_the_desktop_api_skips_the_csrf_check(): void {
        $middleware = app(VerifyCsrfToken::class);
        $inExceptArray = new ReflectionMethod($middleware, 'inExceptArray');
        $skipped = [];

        foreach (Route::getRoutes() as $route) {
            $uri = $route->uri();
            if (str_starts_with($uri, 'api/') || !array_intersect($route->methods(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
                continue;
            }
            $path = preg_replace('/\{[^}]+\}/', '1', $uri);
            if ($inExceptArray->invoke($middleware, Request::create('/' . $path, 'POST'))) {
                $skipped[] = $uri;
            }
        }

        $this->assertSame([], $skipped);
    }
}

<?php

namespace Tests\Unit;

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Http\Request;
use ReflectionMethod;
use Tests\TestCase;

class LoginThrottleKeyTest extends TestCase {
    public function test_ipv6_addresses_of_one_network_share_the_login_throttle(): void {
        $this->assertSame($this->throttleKey('2001:db8:1:2::5'), $this->throttleKey('2001:db8:1:2::6'));
        $this->assertNotSame($this->throttleKey('203.0.113.5'), $this->throttleKey('203.0.113.6'));
    }

    private function throttleKey(string $address): string {
        $request = Request::create('/login', 'POST', ['email' => 'Member@Example.com'], server: ['REMOTE_ADDR' => $address]);

        return (new ReflectionMethod(LoginController::class, 'throttleKey'))->invoke(app(LoginController::class), $request);
    }
}

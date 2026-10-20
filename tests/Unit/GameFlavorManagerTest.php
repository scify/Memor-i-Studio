<?php

namespace Tests\Unit;

use App\BusinessLogicLayer\managers\GameFlavorManager;
use App\StorageLayer\UserRepository;
use Mockery\MockInterface;
use RuntimeException;
use Tests\TestCase;

class GameFlavorManagerTest extends TestCase {
    public function test_game_flavor_list_keeps_an_unexpected_exception(): void {
        $this->mock(UserRepository::class, function (MockInterface $mock) {
            $mock->shouldReceive('find')->andThrow(new RuntimeException('user lookup failed'));
        });

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('user lookup failed');

        app(GameFlavorManager::class)->getGameFlavors(1, 1);
    }
}

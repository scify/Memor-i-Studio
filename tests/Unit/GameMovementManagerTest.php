<?php

namespace Tests\Unit;

use App\BusinessLogicLayer\managers\GameMovementManager;
use App\BusinessLogicLayer\managers\GameRequestManager;
use App\BusinessLogicLayer\managers\PlayerManager;
use App\Models\GameRequest;
use App\Models\Player;
use App\StorageLayer\GameMovementStorage;
use App\Utils\ServerResponses;
use Mockery;
use Tests\TestCase;

class GameMovementManagerTest extends TestCase {
    public function test_latest_movement_reports_an_offline_opponent(): void {
        $gameRequest = new GameRequest;
        $gameRequest->id = 7;
        $opponent = new Player;

        $gameRequestManager = Mockery::mock(GameRequestManager::class);
        $gameRequestManager->shouldReceive('getGameRequest')->with(7)->andReturn($gameRequest);
        $playerManager = Mockery::mock(PlayerManager::class);
        $playerManager->shouldReceive('getPlayerById')->with(3)->andReturn($opponent);
        $playerManager->shouldReceive('isPlayerOnline')->with($opponent)->andReturn(false);

        $manager = new GameMovementManager(Mockery::mock(GameMovementStorage::class), $gameRequestManager, $playerManager);

        $response = $manager->getLatestOpponentGameMovement([
            'game_request_id' => 7,
            'opponent_id' => 3,
            'last_timestamp' => 0,
        ]);

        $this->assertSame(ServerResponses::$OPPONENT_OFFLINE, $response->code, (string) json_encode($response->parameters));
    }
}

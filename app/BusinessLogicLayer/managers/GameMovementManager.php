<?php
/**
 * Created by IntelliJ IDEA.
 * User: pisaris
 * Date: 29/8/2017
 * Time: 4:01 μμ
 */

namespace App\BusinessLogicLayer\managers;

use App\Models\api\ApiOperationResponse;
use App\Models\GameMovement;
use App\StorageLayer\GameMovementStorage;
use App\Utils\ServerResponses;
use Exception;

class GameMovementManager {

    private $gameMovementStorage;

    function __construct() {
        $this->gameMovementStorage = new GameMovementStorage();
    }

    public function createGameMovement(array $input) {
        try {
            $gameRequestManager = new GameRequestManager();
            $gameRequest = $gameRequestManager->getGameRequest($input['game_request_id']);
            $newGameMovement = $this->create($input['player_id'], $input['movement_json'], $gameRequest->id, $input['timestamp']);
            return new ApiOperationResponse(ServerResponses::$RESPONSE_SUCCESSFUL, 'game_movement_created', ["game_movement_id" => $newGameMovement->id]);
        } catch (Exception $e) {
            return new ApiOperationResponse(ServerResponses::$RESPONSE_ERROR, 'error', $e->getMessage());
        }
    }

    private function create($playerId, $movementJson, $gameRequestId, $timestamp) {
        $gameMovement = new GameMovement([
            'player_id' => $playerId,
            'movement_json' => $movementJson,
            'game_request_id' => $gameRequestId,
            'timestamp' => $timestamp
        ]);

        return $this->gameMovementStorage->saveGameMovement($gameMovement);
    }

    public function getLatestOpponentGameMovement($input) {
        try {
            $playerManager = new PlayerManager();
            $gameRequestManager = new GameRequestManager();
            $gameRequest = $gameRequestManager->getGameRequest($input['game_request_id']);
            $opponent = $gameRequest->opponent;
            if(!$playerManager->isPlayerOnline($opponent))
                return new ApiOperationResponse(ServerResponses::$OPPONENT_OFFLINE, 'opponent_offline', "");

            $gameMovement = $this->gameMovementStorage->getLatestGameMovementOfPlayer($gameRequest->id, $input['opponent_id'], $input['last_timestamp']);
            if($gameMovement) {
                $gameMovement->delete();
                return new ApiOperationResponse(ServerResponses::$RESPONSE_SUCCESSFUL, 'new_movement',
                    [
                        "game_movement_json" => json_decode($gameMovement->movement_json),
                        "timestamp" => $gameMovement->timestamp
                    ]);
            }
            else
                return new ApiOperationResponse(ServerResponses::$RESPONSE_EMPTY, 'no_movement', "");


        } catch (Exception $e) {
            return new ApiOperationResponse(ServerResponses::$RESPONSE_ERROR, 'error', $e->getMessage());
        }
    }
}
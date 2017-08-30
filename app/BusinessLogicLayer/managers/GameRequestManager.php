<?php
/**
 * Created by IntelliJ IDEA.
 * User: pisaris
 * Date: 29/8/2017
 * Time: 4:01 μμ
 */

namespace App\BusinessLogicLayer\managers;


use App\BusinessLogicLayer\GameRequestStatus;
use App\Models\api\ApiOperationResponse;
use App\Models\GameRequest;
use App\StorageLayer\GameRequestStorage;
use Exception;

class GameRequestManager {

    private $gameRequestStorage;

    function __construct() {
        $this->gameRequestStorage = new GameRequestStorage();
    }

    public function getGameRequestsForPlayer($input) {
        $playerId = $input['player_id'];
        $playerManager = new PlayerManager();
        $player = $playerManager->getPlayerById($playerId);
        if(!$player)
            return new ApiOperationResponse(2, 'player_not_found', "");
        $playerManager->markPlayerAsActive($player);
        $gameRequest = $this->gameRequestStorage->getGameRequestsForOpponent($playerId)->first();
        if($gameRequest)
            return new ApiOperationResponse(1, 'new_request', $gameRequest);
        return new ApiOperationResponse(1, 'no_requests', null);
    }

    private function getGameRequest($gameRequestId) {
        return $this->gameRequestStorage->getGameRequestById($gameRequestId);
    }

    public function initiateGameRequest(array $input) {
        try {
            $gameFlavorManager = new GameFlavorManager();
            $gameFlavorId = $gameFlavorManager->getFlavorIdFromIdentifier($input['game_identifier']);
            $newGameRequest = $this->create($input['player_initiator_id'], $input['player_opponent_id'], $gameFlavorId, $input['game_level_id']);
            return new ApiOperationResponse(1, 'game_request_created', ["game_request_id" => $newGameRequest->id]);
        } catch (Exception $e) {
            return new ApiOperationResponse(2, 'error', $e->getMessage());
        }
    }

    private function create($playerInitiatorId, $playerOpponentId, $gameFlavorId, $gameLevelId) {
        $gameRequest = new GameRequest([
            'player_initiator_id' => $playerInitiatorId,
            'player_opponent_id' => $playerOpponentId,
            'game_flavor_id' => $gameFlavorId,
            'game_level_id' => $gameLevelId,
            'status_id' => GameRequestStatus::REQUEST_SENT
        ]);

        return $this->gameRequestStorage->saveGameRequest($gameRequest);
    }
}
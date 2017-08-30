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

    public function getReplyForGameRequest(array $input) {
        try {
            $gameRequest = $this->getGameRequest($input['game_request_id']);
            // if status is still 'sent', then there has been no reply yet, so return appropriate code
            if($gameRequest->status_id == GameRequestStatus::REQUEST_SENT) {
                return new ApiOperationResponse(4, 'not_replied', '');
            } else {
                if($gameRequest->status_id == GameRequestStatus::ACCEPTED_BY_OPPONENT) {
                    return new ApiOperationResponse(1, 'accepted', 'accepted by opponent');
                } else if($gameRequest->status_id == GameRequestStatus::REJECTED_BY_OPPONENT) {
                    return new ApiOperationResponse(1, 'rejected', 'rejected by opponent');
                }
            }
            // else, send response with game request status
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
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
use App\Models\Player;
use App\StorageLayer\GameRequestStorage;
use App\Utils\ServerResponses;
use DateTime;
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
            return new ApiOperationResponse(ServerResponses::$RESPONSE_ERROR, 'player_not_found', "");
        $playerManager->markPlayerAsOnline($player);
        $gameRequest = $this->gameRequestStorage->getGameRequestsForOpponent($playerId)->first();

        if($gameRequest) {
            $initiatorUserName = $gameRequest->initiator->user_name;
            $initiatorId = $gameRequest->initiator->id;
            return new ApiOperationResponse(ServerResponses::$RESPONSE_SUCCESSFUL, 'new_request',
                [
                    "game_request_id" => $gameRequest->id,
                    "initiator_user_name" => $initiatorUserName,
                    "initiator_id" => $initiatorId,
                    "game_level_id" => $gameRequest->game_level_id
                ]);
        }
        return null;
    }

    public function getGameRequest($gameRequestId) {
        $gameRequest = $this->gameRequestStorage->getGameRequestById($gameRequestId);
        if($gameRequest)
            return $gameRequest;
        throw new Exception("Game request with id: " . $gameRequestId . " not found!");
    }

    public function initiateGameRequest(array $input) {
        $gameFlavorManager = new GameFlavorManager();
        $playerManager = new PlayerManager();
        try {
            $initiatorPlayer = $playerManager->getPlayerById($input['player_initiator_id']);
            $opponentPlayer = $playerManager->getPlayerById($input['player_opponent_id']);

            $gameFlavorId = $gameFlavorManager->getFlavorIdFromIdentifier($input['game_identifier']);
            // check if there is a request from the opponent player that is pending, was initiated less than 30 seconds ago
            // if there is , return appropriate code message
            $newGameRequest = $this->create($initiatorPlayer->id, $opponentPlayer->id, $gameFlavorId, $input['game_level_id']);
            return new ApiOperationResponse(ServerResponses::$RESPONSE_SUCCESSFUL, 'game_request_created', ["game_request_id" => $newGameRequest->id]);

        } catch (Exception $e) {
            return new ApiOperationResponse(ServerResponses::$RESPONSE_ERROR, 'error', $e->getMessage());
        }
    }

    public function playersAlreadyHaveOpenRequest($initiatorPlayerId, $opponentPlayerId) {
        $date = new DateTime("30 seconds ago");
        $gameRequests = $this->gameRequestStorage->getGameRequestsNewerThan($initiatorPlayerId, $opponentPlayerId, $date);
        return !$gameRequests->isEmpty();
    }

    public function playerExistsAsOpponentInAnotherRequest($opponentPlayerId) {
        $date = new DateTime("30 seconds ago");
        $gameRequests = $this->gameRequestStorage->getGameRequestsForOpponentNewerThan($opponentPlayerId, $date);
        return !$gameRequests->isEmpty();
    }

    public function deleteOldGameRequests($initiatorPlayerId) {
        $date = new DateTime("10 seconds ago");
        $gameRequests = $this->gameRequestStorage->getGameRequestsForInitiatorOlderThan($initiatorPlayerId, $date);
        foreach ($gameRequests as $gameRequest) {
            $gameRequest->delete();
        }
    }

    public function getReplyForGameRequest(array $input) {
        try {
            $gameRequest = $this->getGameRequest($input['game_request_id']);
            // if status is still 'sent', then there has been no reply yet, so return appropriate code
            if($gameRequest->status_id == GameRequestStatus::REQUEST_SENT) {
                return new ApiOperationResponse(ServerResponses::$RESPONSE_EMPTY, 'not_replied', '');
            } else {
                if($gameRequest->status_id == GameRequestStatus::ACCEPTED_BY_OPPONENT) {
                    return new ApiOperationResponse(ServerResponses::$RESPONSE_SUCCESSFUL, 'accepted', 'accepted by opponent');
                } else if($gameRequest->status_id == GameRequestStatus::REJECTED_BY_OPPONENT) {
                    return new ApiOperationResponse(ServerResponses::$RESPONSE_SUCCESSFUL, 'rejected', 'rejected by opponent');
                }
            }
            // else, send response with game request status
        } catch (Exception $e) {
            return new ApiOperationResponse(ServerResponses::$RESPONSE_ERROR, 'error', $e->getMessage());
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

    public function setShuffledCardsForGame(array $input) {
        try {
            $gameRequest = $this->getGameRequest($input['game_request_id']);
            $gameRequest->shuffled_cards = $input['shuffled_cards'];
            $gameRequest = $this->gameRequestStorage->saveGameRequest($gameRequest);
            $playerManager = new PlayerManager();
            $initiatorPlayer = $gameRequest->initiator;
            $opponentPlayer = $gameRequest->opponent;
            $playerManager->markPlayerAsInGame($initiatorPlayer);
            $playerManager->markPlayerAsInGame($opponentPlayer);
            return new ApiOperationResponse(ServerResponses::$RESPONSE_SUCCESSFUL, 'success', '');
        } catch (Exception $e) {
            return new ApiOperationResponse(ServerResponses::$RESPONSE_ERROR, 'error', $e->getMessage());
        }
    }

    public function setGameEnded(array $input) {
        try {
            $gameRequest = $this->getGameRequest($input['game_request_id']);
            $initiatorPlayer = $gameRequest->initiator;
            $opponentPlayer = $gameRequest->opponent;
            $playerManager = new PlayerManager();
            $playerManager->markPlayerAsNotInGame($initiatorPlayer);
            $playerManager->markPlayerAsNotInGame($opponentPlayer);
            return $this->updateGameRequestStatusAndGetResponse($gameRequest, GameRequestStatus::COMPLETED);
        } catch (Exception $e) {
            return new ApiOperationResponse(ServerResponses::$RESPONSE_ERROR, 'error', $e->getMessage());
        }
    }

    public function setGameCanceled(array $input) {
        try {
            $gameRequest = $this->getGameRequest($input['game_request_id']);
            $initiatorPlayer = $gameRequest->initiator;
            $opponentPlayer = $gameRequest->opponent;
            $playerManager = new PlayerManager();
            $playerManager->markPlayerAsNotInGame($initiatorPlayer);
            $playerManager->markPlayerAsNotInGame($opponentPlayer);
            return $this->updateGameRequestStatusAndGetResponse($gameRequest, GameRequestStatus::CANCELED);
        } catch (Exception $e) {
            return new ApiOperationResponse(ServerResponses::$RESPONSE_ERROR, 'error', $e->getMessage());
        }
    }

    public function getShuffledCardsForGame(array $input) {
        try {
            $gameRequest = $this->getGameRequest($input['game_request_id']);
            if($gameRequest->shuffled_cards) {
                return new ApiOperationResponse(ServerResponses::$RESPONSE_SUCCESSFUL, 'success', json_decode($gameRequest->shuffled_cards));
            } else {
                return new ApiOperationResponse(ServerResponses::$RESPONSE_EMPTY, 'no_cards', '');
            }

        } catch (Exception $e) {
            return new ApiOperationResponse(ServerResponses::$RESPONSE_ERROR, 'error', $e->getMessage());
        }
    }

    public function replyToGameRequest(array $input) {
        try {
            $replyStr = $input['accepted'];
            $gameRequestId = $input['game_request_id'];
            $gameRequest = $this->getGameRequest($gameRequestId);
            if($replyStr == 'true') {
                return $this->updateGameRequestStatusAndGetResponse($gameRequest, GameRequestStatus::ACCEPTED_BY_OPPONENT);
            }
            else if($replyStr == 'false') {
                return $this->updateGameRequestStatusAndGetResponse($gameRequest, GameRequestStatus::REJECTED_BY_OPPONENT);
            }
            else
                return new ApiOperationResponse(ServerResponses::$RESPONSE_ERROR, 'error_wrong_parameters', 'The reply parameter was neither "true" or "false');
        } catch (Exception $e) {
            return new ApiOperationResponse(ServerResponses::$RESPONSE_ERROR, 'error', $e->getMessage());
        }
    }

    public function updateGameRequestStatusAndGetResponse(GameRequest $gameRequest, $status) {
        $gameRequest->status_id = $status;
        $this->gameRequestStorage->saveGameRequest($gameRequest);
        return new ApiOperationResponse(ServerResponses::$RESPONSE_SUCCESSFUL, 'success', 'Game request status updated');
    }

    public function getOpenRequestsForPlayer(Player $player) {
        return $this->gameRequestStorage->getGameRequestsForOpponent($player->id);
    }
}
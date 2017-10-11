<?php
/**
 * Created by IntelliJ IDEA.
 * User: pisaris
 * Date: 29/8/2017
 * Time: 4:01 μμ
 */

namespace App\StorageLayer;


use App\BusinessLogicLayer\GameRequestStatus;
use App\Models\GameRequest;

class GameRequestStorage {

    public function saveGameRequest(GameRequest $gameRequest) {
        $gameRequest->save();
        return $gameRequest;
    }

    public function getGameRequestById($gameRequestId) {
        return GameRequest::find($gameRequestId);
    }

    public function getGameRequestsForOpponent($playerOpponentId) {
        return GameRequest::where([
            ['player_opponent_id', $playerOpponentId],
            ['status_id', GameRequestStatus::REQUEST_SENT]
        ])->orderBy('created_at', 'desc')->get();
    }

    public function getGameRequestsForInitiatorOlderThan($initiatorPlayerId, $date) {
        return GameRequest::where([
            ['updated_at', '<=', $date],
            ['player_initiator_id', $initiatorPlayerId]
        ])->orderBy('created_at', 'desc')->get();
    }

    public function getGameRequestsNewerThan($initiatorPlayerId, $opponentPlayerId, $date) {
        return GameRequest::where([
            ['updated_at', '>=', $date],
            ['player_initiator_id', $initiatorPlayerId],
            ['player_opponent_id', $opponentPlayerId],
            ['status_id', GameRequestStatus::REQUEST_SENT]
        ])->orderBy('created_at', 'desc')->get();
    }

    public function getGameRequestsForOpponentNewerThan($opponentPlayerId, $date) {
        return GameRequest::where([
            ['updated_at', '>=', $date],
            ['player_opponent_id', $opponentPlayerId],
            ['status_id', GameRequestStatus::REQUEST_SENT]
        ])->orderBy('created_at', 'desc')->get();
    }

}
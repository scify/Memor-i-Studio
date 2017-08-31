<?php
/**
 * Created by IntelliJ IDEA.
 * User: pisaris
 * Date: 29/8/2017
 * Time: 4:01 μμ
 */

namespace App\StorageLayer;


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
            ['player_opponent_id', $playerOpponentId]
        ])->get();
    }

}
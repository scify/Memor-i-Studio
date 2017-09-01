<?php

namespace App\StorageLayer;


use App\Models\GameMovement;

class GameMovementStorage {

    public function saveGameMovement(GameMovement $gameMovement) {
        $gameMovement->save();
        return $gameMovement;
    }

    public function getGameMovementById($gameMovementId) {
        return GameMovement::find($gameMovementId);
    }

    public function getLatestGameMovementForGameByPlayerId($gameRequestId, $playerId) {
        return GameMovement::where([
            ['player_id', $playerId],
            ['game_request_id', $gameRequestId]
        ])->orderBy('timestamp', 'desc')->first();
    }
}
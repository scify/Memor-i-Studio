<?php
/**
 * Created by IntelliJ IDEA.
 * User: pisaris
 * Date: 28/8/2017
 * Time: 10:44 πμ
 */

namespace App\StorageLayer;


use App\Models\Player;
use DateTime;

class PlayerStorage {

    public function savePlayer(Player $player) {
        $player->save();
        return $player;
    }

    public function getPlayerById($playerId) {
        return Player::find($playerId);
    }

    public function getPlayerByUserName($userName) {
        return Player::where([
            ['user_name', (string)$userName]
        ])->get();
    }

    public function getPlayerByUserNameAndId($userName, $playerId) {
        return Player::where([
            ['user_name', (string)$userName],
            ['id', $playerId]
        ])->first();
    }

    public function getOnlinePlayersExcept($playerId) {
        $date = new DateTime;
        $date->modify('-10 seconds');
        $formatted_date = $date->format('Y-m-d H:i:s');
        return Player::where([
            ['last_seen_online', '>=', $formatted_date],
            ['in_game', false],
            ['id', '<>' , $playerId]
        ])->get();
    }

    public function getOnlinePlayersForGameFlavorExcept($gameFlavorId, $playerId) {
        $date = new DateTime;
        $date->modify('-10 seconds');
        $formatted_date = $date->format('Y-m-d H:i:s');
        return Player::where([
            ['last_seen_online', '>=', $formatted_date],
            ['in_game', false],
            ['game_flavor_playing', $gameFlavorId],
            ['id', '<>' , $playerId]
        ])->get();
    }

}
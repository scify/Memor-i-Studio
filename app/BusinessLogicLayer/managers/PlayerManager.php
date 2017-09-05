<?php
/**
 * Created by IntelliJ IDEA.
 * User: pisaris
 * Date: 28/8/2017
 * Time: 10:42 πμ
 */

namespace App\BusinessLogicLayer\managers;


use App\Models\api\ApiOperationResponse;
use App\Models\GameFlavor;
use App\Models\Player;
use App\StorageLayer\PlayerStorage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;


class PlayerManager {

    private $playerStorage;

    function __construct() {
        $this->playerStorage = new PlayerStorage();
    }

    public function playerWithUserNameExists($userName) {
        return $this->playerStorage->getPlayerByUserName($userName)->count() != 0;
    }

    public function getOnlinePlayers(array $input) {
        $playerIdToBeExcluded = $input['player_id'];
        $players = $this->playerStorage->getOnlinePlayersExcept($playerIdToBeExcluded);
        return $players;
    }


    public function markPlayerAsActive(Player $player) {
        $player->last_seen_online = Carbon::now()->toDateTimeString();
        $this->playerStorage->savePlayer($player);
    }

    public function markPlayerAsInGame(Player $player) {
        $player->in_game = true;
        $this->playerStorage->savePlayer($player);
    }

    public function markPlayerAsNotInGame(Player $player) {
        $player->in_game = false;
        $this->playerStorage->savePlayer($player);
    }

    public function registerNewPlayer(array $input) {
        $username = $input['user_name'];
        $password = $input['password'];
        $gameFlavorPackIdentifier = $input['game_flavor_pack_identifier'];
        try {
            if($this->playerWithUserNameExists($username)) {
                return new ApiOperationResponse(2, "user_name_taken", "");
            } else {
                $newPlayer = new Player([
                    'user_name' => $username,
                    'password' => Hash::make($password)
                ]);
                $newPlayer = $this->playerStorage->savePlayer($newPlayer);
                $this->markPlayerAsActive($newPlayer);
                $this->makePlayerOnlineForGameFlavor($newPlayer, $gameFlavorPackIdentifier);
                return new ApiOperationResponse(1, 'player_created', ["player_id" => $newPlayer->id]);
            }
        }
        catch (Exception $e) {
            return new ApiOperationResponse(2, 'error', $e->getMessage());
        }
    }

    private function makePlayerOnlineForGameFlavor(Player $player, $gameFlavorPackIdentifier) {
        $gameFlavorManager = new GameFlavorManager();
        $gameFlavor = $gameFlavorManager->getGameFlavorByGameIdentifier($gameFlavorPackIdentifier);
        $player->game_flavor_playing = $gameFlavor->id;
        $this->playerStorage->savePlayer($player);
    }

    public function logInPlayer($input) {
        $username = $input['user_name'];
        $password = $input['password'];
        $gameFlavorPackIdentifier = $input['game_flavor_pack_identifier'];
        try {
            if ($this->playerWithUserNameExists($username)) {
                $player = $this->getPlayerByUserName($username);
                if (Hash::check($password, $player->password)) {
                    $this->markPlayerAsActive($player);
                    $this->makePlayerOnlineForGameFlavor($player, $gameFlavorPackIdentifier);
                    return new ApiOperationResponse(1, 'player_found', ["player_id" => $player->id]);
                } else {
                    return new ApiOperationResponse(2, 'player_not_found', "");
                }
            } else {
                return new ApiOperationResponse(2, 'player_not_found', "");
            }
        }
        catch (Exception $e) {
                return new ApiOperationResponse(2, 'error', $e->getMessage());
            }
    }

    private function getPlayerByUserName($username) {
        return $this->playerStorage->getPlayerByUserName($username)->first();
    }

    public function getPlayerById($playerId) {
        return $this->playerStorage->getPlayerById($playerId);
    }

    public function getPlayerAvailabilityForGameFlavor(array $input) {
        $playerUserName = $input['user_name'];
        $gameFlavorIdentifier = $input['game_flavor_pack_identifier'];
        $gameFlavorManager = new GameFlavorManager();
        try {
            $gameFlavor = $gameFlavorManager->getGameFlavorByGameIdentifier($gameFlavorIdentifier);
            if($this->playerWithUserNameExists($playerUserName)) {
                $player = $this->getPlayerByUserName($playerUserName);
                if($this->isPlayerAvailableForGameFlavor($player, $gameFlavor)) {
                    return new ApiOperationResponse(1, 'player_available', ["player_id" => $player->id]);
                } else {
                    return new ApiOperationResponse(1, 'player_not_available', "");
                }
            } else {
                return new ApiOperationResponse(4, 'player_not_found', "");
            }
        } catch (Exception $e) {
            return new ApiOperationResponse(2, 'error', $e->getMessage());
        }
    }

    public function isPlayerAvailableForGameFlavor(Player $player, GameFlavor $gameFlavor) {
        $playerLastSeenOnlineMargin = strtotime("-5 minutes");

        if ($playerLastSeenOnlineMargin > strtotime($player->last_seen_online) || $player->game_flavor_playing != $gameFlavor->id){
            return false;
        }
        return true;
    }

    public function markPlayerActive($input) {
        $playerId = $input['player_id'];
        $player = $this->getPlayerById($playerId);
        if(!$player)
            return new ApiOperationResponse(2, 'player_not_found', "");
        try {
            $this->markPlayerAsActive($player);
            return new ApiOperationResponse(1, 'game_marked_active', "");
        } catch (Exception $e) {
            return new ApiOperationResponse(2, 'error', $e->getMessage());
        }
    }
}
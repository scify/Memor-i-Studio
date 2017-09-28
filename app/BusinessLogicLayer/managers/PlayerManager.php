<?php
/**
 * Created by IntelliJ IDEA.
 * User: pisaris
 * Date: 28/8/2017
 * Time: 10:42 πμ
 */

namespace App\BusinessLogicLayer\managers;


use App\BusinessLogicLayer\GameRequestStatus;
use App\Models\api\ApiOperationResponse;
use App\Models\GameFlavor;
use App\Models\Player;
use App\StorageLayer\PlayerStorage;
use App\Utils\ServerResponses;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Hash;
use Exception;

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


    public function markPlayerAsOnline(Player $player) {
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
            $gameFlavorManager = new GameFlavorManager();
            $gameFlavor = $gameFlavorManager->getGameFlavorByGameIdentifier($gameFlavorPackIdentifier);
            if($this->playerWithUserNameExists($username)) {
                return new ApiOperationResponse(ServerResponses::$RESPONSE_ERROR, "user_name_taken", "");
            } else {
                $newPlayer = new Player([
                    'user_name' => $username,
                    'password' => Hash::make($password)
                ]);
                $newPlayer = $this->playerStorage->savePlayer($newPlayer);
                $this->markPlayerAsOnline($newPlayer);
                $this->makePlayerOnlineForGameFlavor($newPlayer, $gameFlavorPackIdentifier);
                return new ApiOperationResponse(ServerResponses::$RESPONSE_SUCCESSFUL, 'player_created', ["player_id" => $newPlayer->id]);
            }
        }
        catch (Exception $e) {
            return new ApiOperationResponse(ServerResponses::$RESPONSE_ERROR, 'error', $e->getMessage());
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
                    $this->markPlayerAsOnline($player);
                    $this->markPlayerAsNotInGame($player);
                    $this->makePlayerOnlineForGameFlavor($player, $gameFlavorPackIdentifier);
                    return new ApiOperationResponse(ServerResponses::$RESPONSE_SUCCESSFUL, 'player_found', ["player_id" => $player->id]);
                } else {
                    return new ApiOperationResponse(ServerResponses::$RESPONSE_EMPTY, 'player_not_found', "");
                }
            } else {
                return new ApiOperationResponse(ServerResponses::$RESPONSE_EMPTY, 'player_not_found', "");
            }
        }
        catch (Exception $e) {
                return new ApiOperationResponse(ServerResponses::$RESPONSE_ERROR, 'error', $e->getMessage());
            }
    }

    private function getPlayerByUserName($username) {
        return $this->playerStorage->getPlayerByUserName($username)->first();
    }

    public function getPlayerById($playerId) {
        $player = $this->playerStorage->getPlayerById($playerId);
        if(!$player)
            throw new Exception("player with id: " . $playerId . " not found.");
        return $player;
    }

    public function getPlayerAvailabilityForGameFlavor(array $input) {
        $playerUserName = $input['user_name'];
        $gameFlavorIdentifier = $input['game_flavor_pack_identifier'];
        $initiatorPlayer = $this->getPlayerById($input['player_initiator_id']);
        $gameRequestManager = new GameRequestManager();
        $gameFlavorManager = new GameFlavorManager();
        try {
            $gameRequestManager->deleteOldGameRequests($initiatorPlayer->id);
            $gameFlavor = $gameFlavorManager->getGameFlavorByGameIdentifier($gameFlavorIdentifier);
            if($this->playerWithUserNameExists($playerUserName)) {
                $opponentPlayer = $this->getPlayerByUserName($playerUserName);
                if($gameRequestManager->playersAlreadyHaveOpenRequest($opponentPlayer->id, $initiatorPlayer->id)) {
                    return new ApiOperationResponse(ServerResponses::$OPPONENT_HAS_ALREADY_SENT_REQUEST, 'game_request_exists', "");
                } else {
                    if ($this->isPlayerAvailableForGameFlavor($opponentPlayer, $gameFlavor)) {
                        return new ApiOperationResponse(ServerResponses::$RESPONSE_SUCCESSFUL, 'player_available', ["player_id" => $opponentPlayer->id]);
                    } else {
                        return new ApiOperationResponse(ServerResponses::$RESPONSE_SUCCESSFUL, 'player_not_available', "");
                    }
                }
            } else {
                return new ApiOperationResponse(ServerResponses::$RESPONSE_EMPTY, 'player_not_found', "");
            }
        } catch (Exception $e) {
            return new ApiOperationResponse(ServerResponses::$RESPONSE_ERROR, 'error', $e->getMessage());
        }
    }

    public function getRandomPlayer(array $input) {
        $gameFlavorIdentifier = $input['game_flavor_pack_identifier'];
        $playerId = $input['player_id'];
        $gameFlavorManager = new GameFlavorManager();
        $gameRequestManager = new GameRequestManager();
        try {
            $player = $this->getPlayerById($playerId);
            $gameRequestManager->deleteOldGameRequests($player->id);
            $gameFlavor = $gameFlavorManager->getGameFlavorByGameIdentifier($gameFlavorIdentifier);
            if($gameRequestManager->playerExistsAsOpponentInAnotherRequest($player->id)) {
                return new ApiOperationResponse(ServerResponses::$OPPONENT_HAS_ALREADY_SENT_REQUEST, 'game_request_exists', "");
            }
            $players = $this->playerStorage->getOnlinePlayersForGameFlavorExcept($gameFlavor->id, $playerId);
            if($players->isNotEmpty()) {
                $randomPlayer = $players->random();
                return new ApiOperationResponse(ServerResponses::$RESPONSE_SUCCESSFUL, 'player_found', ["player_id" => $randomPlayer->id]);
            } else {
                return new ApiOperationResponse(ServerResponses::$RESPONSE_EMPTY, 'player_not_found', "");
            }
        } catch (Exception $e) {
            return new ApiOperationResponse(ServerResponses::$RESPONSE_ERROR, 'error', $e->getMessage());
        }
    }

    public function isPlayerAvailableForGameFlavor(Player $player, GameFlavor $gameFlavor) {
        if (!$this->isPlayerOnline($player) || $player->game_flavor_playing != $gameFlavor->id || $player->in_game){
            return false;
        }
        return true;
    }

    public function isPlayerOnline(Player $player) {
        $lastSeenOnline = new DateTime($player->last_seen_online);

        $minutes = new DateTime("10 seconds ago");
        $newDateTime = $minutes->format("Y-m-d H:i:s");
        $lastSeenDate = $lastSeenOnline->format("Y-m-d H:i:s");
        if ($newDateTime > $lastSeenDate){
            return false;
        }
        return true;
    }

    public function markPlayerOnline($input) {
        $playerId = $input['player_id'];
        try {
            $player = $this->getPlayerById($playerId);
            $this->markPlayerAsOnline($player);
            return new ApiOperationResponse(ServerResponses::$RESPONSE_SUCCESSFUL, 'game_marked_active', "");
        } catch (Exception $e) {
            return new ApiOperationResponse(ServerResponses::$RESPONSE_ERROR, 'error', $e->getMessage());
        }
    }

    private function closeAllOpenRequestsForPlayer(Player $player) {
        $gameRequestManager = new GameRequestManager();
        $openRequests = $gameRequestManager->getOpenRequestsForPlayer($player);
        foreach ($openRequests as $openRequest) {
            $gameRequestManager->updateGameRequestStatusAndGetResponse($openRequest, GameRequestStatus::CANCELED);
        }
    }
}
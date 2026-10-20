<?php

namespace App\Http\Controllers;

use App\BusinessLogicLayer\managers\PlayerManager;
use App\Models\api\ApiOperationResponse;
use App\Utils\ServerResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PlayerController extends Controller {
    private $playerManager;

    public function __construct(PlayerManager $playerManager) {
        $this->playerManager = $playerManager;
    }

    public function getOnlinePlayersExcept(Request $request) {
        $validator = Validator::make($request->all(), [
            'player_id' => 'required|integer',
        ]);
        if ($validator->fails()) {
            return json_encode(new ApiOperationResponse(ServerResponses::$VALIDATION_ERROR, 'validation_failed', $validator->messages()));
        }
        $input = $request->all();

        return json_encode($this->playerManager->getOnlinePlayers($input));
    }

    public function registerNewPlayer(Request $request) {
        $validator = Validator::make($request->all(), [
            'user_name' => 'required|string',
            'password' => 'required|min:2',
            'game_flavor_pack_identifier' => 'required|string',
        ]);
        if ($validator->fails()) {
            $response = new ApiOperationResponse(ServerResponses::$VALIDATION_ERROR, 'validation_failed', $validator->messages());
        } else {
            $input = $request->all();
            $response = $this->playerManager->registerNewPlayer($input);
        }

        return json_encode($response);
    }

    public function logInPlayer(Request $request) {
        $validator = Validator::make($request->all(), [
            'user_name' => 'required|string',
            'password' => 'required|min:2',
            'game_flavor_pack_identifier' => 'required|string',
        ]);
        if ($validator->fails()) {
            $response = new ApiOperationResponse(ServerResponses::$VALIDATION_ERROR, 'validation_failed', $validator->messages());
        } else {
            $input = $request->all();
            $response = $this->playerManager->logInPlayer($input);
        }

        return json_encode($response);
    }

    public function setPlayerNotInGame(Request $request) {
        $validator = Validator::make($request->all(), [
            'player_id' => 'required|integer',
        ]);
        if ($validator->fails()) {
            $response = new ApiOperationResponse(ServerResponses::$VALIDATION_ERROR, 'validation_failed', $validator->messages());
        } else {
            $input = $request->all();
            $response = $this->playerManager->setPlayerAsNotInGame($input);
        }

        return json_encode($response);
    }

    public function getPlayerAvailability(Request $request) {
        $validator = Validator::make($request->all(), [
            'user_name' => 'required|string',
            'game_flavor_pack_identifier' => 'required|string',
            'player_initiator_id' => 'required|integer',
        ]);
        if ($validator->fails()) {
            $response = new ApiOperationResponse(ServerResponses::$VALIDATION_ERROR, 'validation_failed', $validator->messages());
        } else {
            $input = $request->all();
            $response = $this->playerManager->getPlayerAvailabilityForGameFlavor($input);
        }

        return json_encode($response);
    }

    public function getRandomPlayer(Request $request) {
        $validator = Validator::make($request->all(), [
            'player_id' => 'required|integer',
            'game_flavor_pack_identifier' => 'required|string',
        ]);
        if ($validator->fails()) {
            $response = new ApiOperationResponse(ServerResponses::$VALIDATION_ERROR, 'validation_failed', $validator->messages());
        } else {
            $input = $request->all();
            $response = $this->playerManager->getRandomPlayer($input);
        }

        return json_encode($response);
    }

    public function setPlayerOnline(Request $request) {
        $validator = Validator::make($request->all(), [
            'player_id' => 'required|integer',
        ]);
        if ($validator->fails()) {
            $response = new ApiOperationResponse(ServerResponses::$VALIDATION_ERROR, 'validation_failed', $validator->messages());
        } else {
            $input = $request->all();
            $response = $this->playerManager->setPlayerOnline($input);
        }

        return json_encode($response);
    }
}

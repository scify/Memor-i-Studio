<?php

namespace App\Http\Controllers;

use App\BusinessLogicLayer\managers\PlayerManager;
use App\Models\api\ApiOperationResponse;
use App\Utils\ServerResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PlayerController extends Controller
{

    private $playerManager;

    function __construct() {
        $this->playerManager = new PlayerManager();
    }


    public function getOnlinePlayersExcept(Request $request) {
        $input = $request->all();
        return json_encode($this->playerManager->getOnlinePlayers($input));
    }

    public function registerNewPlayer(Request $request) {
        $validator = Validator::make($request->all(), [
            'user_name' => 'required',
            'password' => 'required|min:2',
            'game_flavor_pack_identifier' => 'required'
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
            'user_name' => 'required',
            'password' => 'required|min:2',
            'game_flavor_pack_identifier' => 'required'
        ]);
        if ($validator->fails()) {
            $response = new ApiOperationResponse(ServerResponses::$VALIDATION_ERROR, 'validation_failed', $validator->messages());
        } else {
            $input = $request->all();
            $response = $this->playerManager->logInPlayer($input);
        }
        return json_encode($response);
    }

    public function getPlayerAvailability(Request $request) {
        $validator = Validator::make($request->all(), [
            'user_name' => 'required',
            'game_flavor_pack_identifier' => 'required',
            'player_initiator_id' => 'required'
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
            'player_id' => 'required',
            'game_flavor_pack_identifier' => 'required'
        ]);
        if ($validator->fails()) {
            $response = new ApiOperationResponse(ServerResponses::$VALIDATION_ERROR, 'validation_failed', $validator->messages());
        } else {
            $input = $request->all();
            $response = $this->playerManager->getRandomPlayer($input);
        }
        return json_encode($response);
    }

    public function markPlayerActive(Request $request) {
        $validator = Validator::make($request->all(), [
            'player_id' => 'required'
        ]);
        if ($validator->fails()) {
            $response = new ApiOperationResponse(ServerResponses::$VALIDATION_ERROR, 'validation_failed', $validator->messages());
        } else {
            $input = $request->all();
            $response = $this->playerManager->markPlayerOnline($input);
        }
        return json_encode($response);
    }
}

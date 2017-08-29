<?php

namespace App\Http\Controllers;

use App\BusinessLogicLayer\managers\PlayerManager;
use App\Models\api\ApiOperationResponse;
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

    public function getGameRequestsForPlayer(Request $request) {
        $input = $request->all();
        return json_encode($this->playerManager->getGameRequestsForPlayer($input));
    }

    public function registerNewPlayer(Request $request) {
        $validator = Validator::make($request->all(), [
            'user_name' => 'required',
            'password' => 'required|min:4',
        ]);
        if ($validator->fails()) {
            $response = new ApiOperationResponse(3, 'validation_failed', $validator->messages());
        } else {
            $input = $request->all();
            $response = $this->playerManager->registerNewPlayer($input);
        }
        return json_encode($response);
    }

    public function logInPlayer(Request $request) {
        $validator = Validator::make($request->all(), [
            'user_name' => 'required',
            'password' => 'required|min:4',
        ]);
        if ($validator->fails()) {
            $response = new ApiOperationResponse(3, 'validation_failed', $validator->messages());
        } else {
            $input = $request->all();
            $response = $this->playerManager->logInPlayer($input);
        }
        return json_encode($response);
    }
}

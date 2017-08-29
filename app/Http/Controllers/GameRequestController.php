<?php
/**
 * Created by IntelliJ IDEA.
 * User: pisaris
 * Date: 29/8/2017
 * Time: 4:01 μμ
 */

namespace App\Http\Controllers;


use App\BusinessLogicLayer\managers\GameRequestManager;
use App\Models\api\ApiOperationResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GameRequestController {

    private $gameRequestManager;

    function __construct() {
        $this->gameRequestManager = new GameRequestManager();
    }

    public function initiateGameRequest(Request $request) {
        $validator = Validator::make($request->all(), [
            'player_initiator_id' => 'required',
            'player_opponent_id' => 'required',
            'game_flavor_token' => 'required',
        ]);
        if ($validator->fails()) {
            $response = new ApiOperationResponse(3, 'validation_failed', $validator->messages());
        } else {
            $input = $request->all();
            $response = $this->gameRequestManager->initiateGameRequest($input);
        }
        return json_encode($response);
    }

    public function getGameRequestsForPlayer(Request $request) {
        $input = $request->all();
        return json_encode($this->gameRequestManager->getGameRequestsForPlayer($input));
    }

}
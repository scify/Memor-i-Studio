<?php
/**
 * Created by IntelliJ IDEA.
 * User: pisaris
 * Date: 29/8/2017
 * Time: 4:01 μμ
 */

namespace App\Http\Controllers;


use App\BusinessLogicLayer\managers\GameMovementManager;
use App\Models\api\ApiOperationResponse;
use App\Utils\ServerResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GameMovementController {

    private $gameMovementManager;

    function __construct(GameMovementManager $gameMovementManager) {
        $this->gameMovementManager = $gameMovementManager;
    }

    public function createGameMovement(Request $request) {
        $validator = Validator::make($request->all(), [
            'player_id' => 'required',
            'movement_json' => 'required',
            'game_request_id' => 'required',
            'timestamp' => 'required',
        ]);
        if ($validator->fails()) {
            $response = new ApiOperationResponse(ServerResponses::$VALIDATION_ERROR, 'validation_failed', $validator->messages());
        } else {
            $input = $request->all();
            $response = $this->gameMovementManager->createGameMovement($input);
        }
        return json_encode($response);
    }

    public function getLatestOpponentGameMovement(Request $request) {
        $validator = Validator::make($request->all(), [
            'opponent_id' => 'required',
            'game_request_id' => 'required',
            'last_timestamp' => 'required',
        ]);
        if ($validator->fails()) {
            $response = new ApiOperationResponse(ServerResponses::$VALIDATION_ERROR, 'validation_failed', $validator->messages());
        } else {
            $input = $request->all();
            $response = $this->gameMovementManager->getLatestOpponentGameMovement($input);
        }
        return json_encode($response);
    }
}

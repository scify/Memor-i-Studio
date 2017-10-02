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
use App\Utils\ServerResponses;
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
            'game_identifier' => 'required',
            'game_level_id' => 'required',
        ]);
        if ($validator->fails()) {
            $response = new ApiOperationResponse(ServerResponses::$VALIDATION_ERROR, 'validation_failed', $validator->messages());
        } else {
            $input = $request->all();
            $response = $this->gameRequestManager->initiateGameRequest($input);
        }
        return json_encode($response);
    }

    public function getReplyForGameRequest(Request $request) {
        $validator = Validator::make($request->all(), [
            'game_request_id' => 'required',
            'opponent_id' => 'required'
        ]);
        if ($validator->fails()) {
            $response = new ApiOperationResponse(ServerResponses::$VALIDATION_ERROR, 'validation_failed', $validator->messages());
        } else {
            $input = $request->all();
            $response = $this->gameRequestManager->getReplyForGameRequest($input);
        }
        return json_encode($response);
    }

    public function getGameRequestsForPlayer(Request $request) {
        $input = $request->all();
        return json_encode($this->gameRequestManager->getGameRequestsForPlayer($input));
    }

    public function setShuffledCardsForGame(Request $request) {
        $validator = Validator::make($request->all(), [
            'shuffled_cards' => 'required',
            'game_request_id' => 'required'
        ]);
        if ($validator->fails()) {
            $response = new ApiOperationResponse(ServerResponses::$VALIDATION_ERROR, 'validation_failed', $validator->messages());
        } else {
            $input = $request->all();
            $response = $this->gameRequestManager->setShuffledCardsForGame($input);
        }
        return json_encode($response);
    }

    public function getShuffledCardsForGame(Request $request) {
        $validator = Validator::make($request->all(), [
            'game_request_id' => 'required',
            'opponent_id' => 'required'
        ]);
        if ($validator->fails()) {
            $response = new ApiOperationResponse(ServerResponses::$VALIDATION_ERROR, 'validation_failed', $validator->messages());
        } else {
            $input = $request->all();
            $response = $this->gameRequestManager->getShuffledCardsForGame($input);
        }
        return json_encode($response);
    }

    public function replyToGameRequest(Request $request) {
        $validator = Validator::make($request->all(), [
            'accepted' => 'required',
            'game_request_id' => 'required'
        ]);
        if ($validator->fails()) {
            $response = new ApiOperationResponse(ServerResponses::$VALIDATION_ERROR, 'validation_failed', $validator->messages());
        } else {
            $input = $request->all();
            $response = $this->gameRequestManager->replyToGameRequest($input);
        }
        return json_encode($response);
    }

    public function endGameRequest(Request $request) {
        $validator = Validator::make($request->all(), [
            'game_request_id' => 'required'
        ]);
        if ($validator->fails()) {
            $response = new ApiOperationResponse(ServerResponses::$VALIDATION_ERROR, 'validation_failed', $validator->messages());
        } else {
            $input = $request->all();
            $response = $this->gameRequestManager->setGameEnded($input);
        }
        return json_encode($response);
    }

    public function cancelGameRequest(Request $request) {
        $validator = Validator::make($request->all(), [
            'game_request_id' => 'required'
        ]);
        if ($validator->fails()) {
            $response = new ApiOperationResponse(ServerResponses::$VALIDATION_ERROR, 'validation_failed', $validator->messages());
        } else {
            $input = $request->all();
            $response = $this->gameRequestManager->setGameCanceled($input);
        }
        return json_encode($response);
    }

}
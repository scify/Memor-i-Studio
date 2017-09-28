<?php
/**
 * Created by IntelliJ IDEA.
 * User: pisaris
 * Date: 28/9/2017
 * Time: 10:12 πμ
 */

namespace App\Utils;


class ServerResponses {
    public static $RESPONSE_SUCCESSFUL = 1;
    public static $RESPONSE_ERROR = 2;
    public static $VALIDATION_ERROR = 3;
    public static $RESPONSE_EMPTY = 4;
    public static $OPPONENT_HAS_ALREADY_SENT_REQUEST = 5;
    public static $OPPONENT_OFFLINE = 6;
}
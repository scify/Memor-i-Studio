<?php
/**
 * Created by IntelliJ IDEA.
 * User: pisaris
 * Date: 28/8/2017
 * Time: 10:57 πμ
 */

namespace App\Models\api;


class ApiOperationResponse {

    public $code;
    public $message;
    public $parameters;

    function __construct($code, $message, $parameters) {
        $this->code = $code;
        $this->message = $message;
        $this->parameters = $parameters;
    }

}
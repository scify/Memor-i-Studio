<?php
/**
 * Created by IntelliJ IDEA.
 * User: pisaris
 * Date: 29/8/2017
 * Time: 4:17 μμ
 */

namespace App\BusinessLogicLayer;


abstract class GameRequestStatus
{
    const REQUEST_SENT = 1;
    const ACCEPTED_BY_OPPONENT = 2;
    const REJECTED_BY_OPPONENT = 3;
    const IN_PROGRESS = 4;
    const COMPLETED = 5;
    const CANCELED = 6;
}
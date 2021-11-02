<?php

namespace HyperSMS;

class HSException extends \Exception
{
    const HTTP_REQUEST_ERROR = '50001';
    const SDK_LOGIN_ERROR = '30001';
    const SDK_ACCESS_ERROR = '30002';

    function __construct($code, $msg = "")
    {
        parent::__construct($msg, $code);
    }
}
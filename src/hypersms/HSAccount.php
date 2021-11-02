<?php

namespace HyperSMS;

class HSAccount
{
    /**
     * @var string
     */
    public $appId;

    /**
     * @var string
     */
    public $appKey;

    /**
     *
     * @param $appId  string Application ID, Provided after account opening
     * @param $appKey string Application Key, Provided after account opening
     */
    public function __construct($appId, $appKey)
    {
        $this->appId = $appId;
        $this->appKey = $appKey;
    }
}
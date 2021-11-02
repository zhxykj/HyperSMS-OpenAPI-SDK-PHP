<?php

namespace HyperSMS;

class HSBatchSendSub
{
    public $subId;
    public $param;
    public $encodeType = 0;


    /**
     *
     * @param $subId string Send number
     * @param array<string>|null $param Set of dynamic parameter values \
     *     Note: The order should correspond to the dynamic parameter (index) in the template
     * @param int $encodeType Optional (0: plaintext, 1: operator encryption) The default is 0
     */
    public function __construct($subId, $param = null, $encodeType = 0)
    {
        $this->subId = $subId;
        $this->param = $param;
        $this->encodeType = $encodeType;
    }
}
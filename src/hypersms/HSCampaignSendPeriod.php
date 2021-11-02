<?php

namespace HyperSMS;

class HSCampaignSendPeriod
{
    public $start;
    public $end;

    /**
     * @param $start string Time period start (format HH:mm:ss)
     * @param $end string    End of time period (format HH:mm:ss)
     */
    public function __construct($start, $end)
    {
        $this->start = $start;
        $this->end = $end;
    }
}
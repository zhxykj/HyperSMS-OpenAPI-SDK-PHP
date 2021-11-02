<?php

namespace HyperSMS;

class HSCampaignProperty
{
    public $startTime;
    public $endTime;

    /**
     * @var HSCampaignSendPeriod
     */
    public $sendPeriod;


    /**
     * @var array<HSBatchSendSub>
     */
    public $subList;


    /**
     * @param $startTime string Campaign start time (format yyyy-MM-dd HH:mm:ss)
     * @param $endTime string Campaign end time (format yyyy-MM-dd HH:mm:ss)
     * @param $subList array<HSBatchSendSub>
     * @param HSCampaignSendPeriod|null $sendPeriod
     */
    public function __construct($startTime, $endTime, $subList, $sendPeriod = null)
    {
        $this->startTime = $startTime;
        $this->endTime = $endTime;
        $this->subList = $subList;

        $this->sendPeriod = $sendPeriod;
    }
}
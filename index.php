<?php

namespace HyperSMS;

require 'vendor/autoload.php';

$APP_ID = '********';
$APP_KEY = '********';
$APP_SECRET = '********';
$GATEWAY_URL = 'https://openapi.viettel.hypersms.vn/v2/';


$account = new HSAccount(
    $APP_ID,
    $APP_KEY);

$token = '****************';


/**
 * Tips: Usually we will call the login once and then put the token in the cache
 */
function demo_test_login()
{
    global $GATEWAY_URL, $account, $APP_SECRET;
    $sdk = new HSSDK(
        $GATEWAY_URL,
        $account);
    try {
        $token = $sdk->login($account->appId, $APP_SECRET);
        var_dump('testLogin token is' . $token . "\n");
    } catch (HSException $e) {
        var_dump($e);
    }
}


function demo_add_brandname()
{
    global $GATEWAY_URL, $account, $token;
    $sdk = new HSSDK(
        $GATEWAY_URL,
        $account,
        $token);

    try {
        $result = $sdk->add_brandname(
            'HY0002',
            "sk send hy 999",
            'http://upload.vn-vtt-test.corporfountain.com/hypersms/20211029/0d337be2b517403fb49d8b5cca43d9f30.jpg?id=2806'
        );
        var_dump($result);
    } catch (HSException $e) {

    }
}


function demo_query_brandname()
{
    global $GATEWAY_URL, $account, $token;
    $sdk = new HSSDK(
        $GATEWAY_URL,
        $account,
        $token);

    try {
        $result = $sdk->query_brandname();
        echo json_encode($result);
    } catch (HSException $e) {

    }
}

function demo_info_brandname()
{
    global $GATEWAY_URL, $account, $token;
    $sdk = new HSSDK(
        $GATEWAY_URL,
        $account,
        $token);

    try {
        $result = $sdk->info_brandname('BR-DxARzDv7xk');
        echo json_encode($result);
    } catch (HSException $e) {

    }
}

function demo_invalid_brandname()
{
    global $GATEWAY_URL, $account, $token;
    $sdk = new HSSDK(
        $GATEWAY_URL,
        $account,
        $token);

    try {
        $result = $sdk->invalid_brandname('BR-DxARzDv7xk');
        echo json_encode($result);
    } catch (HSException $e) {

    }
}


function demo_add_template()
{
    global $GATEWAY_URL, $account, $token;
    $sdk = new HSSDK(
        $GATEWAY_URL,
        $account,
        $token);

    try {
        $result = $sdk->add_template(
            2,
            'BR-19RpPVq940',
            'SMS_CSKH_GROUP_1_FIN',
            'temp title 2',
            'Hi, My name is HAHA');
        echo json_encode($result);
    } catch (HSException $e) {

    }
}

function demo_query_template()
{
    global $GATEWAY_URL, $account, $token;
    $sdk = new HSSDK(
        $GATEWAY_URL,
        $account,
        $token);

    try {
        $result = $sdk->query_template();
        echo json_encode($result);
    } catch (HSException $e) {

    }
}


function demo_edit_template()
{
    global $GATEWAY_URL, $account, $token;
    $sdk = new HSSDK(
        $GATEWAY_URL,
        $account,
        $token);

    try {
        $result = $sdk->edit_template(
            'TE-MGW4PV0pVG',
            2,
            'BR-19RpPVq940',
            'SMS_CSKH_GROUP_1_FIN',
            'ST Test Normal Template MODIFY BY SK',
            'Hi, My name is HAHA');
        echo json_encode($result);
    } catch (HSException $e) {

    }
}


function demo_info_template()
{
    global $GATEWAY_URL, $account, $token;
    $sdk = new HSSDK(
        $GATEWAY_URL,
        $account,
        $token);

    try {
        $result = $sdk->info_template('TE-MGW4PV0pVG');
        echo json_encode($result);
    } catch (HSException $e) {

    }
}


function demo_invalid_template()
{
    global $GATEWAY_URL, $account, $token;
    $sdk = new HSSDK(
        $GATEWAY_URL,
        $account,
        $token);

    try {
        $result = $sdk->invalid_template('TE-MGW4PV0pVG');
        echo json_encode($result);
    } catch (HSException $e) {

    }
}

function demo_send()
{
    global $GATEWAY_URL, $account, $token;
    $sdk = new HSSDK(
        $GATEWAY_URL,
        $account,
        $token);

    try {
        $result = $sdk->send(
            2,
            "TE-vL2GrkwjOX",
            "84984377066",
            ["? /n ! # $ % ( ) * + , - . / : ; ", "noi dung at 1635742975"]
        );
        var_dump($result);
    } catch (HSException $e) {

    }
}


function demo_batch_send()
{
    global $GATEWAY_URL, $account, $token;
    $sdk = new HSSDK(
        $GATEWAY_URL,
        $account,
        $token);

    try {
        $result = $sdk->batch_send(
            2,
            "TE-B4QwOn2zYx",
            array(
                array(
                    "subId" => "091313123121",
                    "param" => ["0", "2"]
                ),
                array(
                    "subId" => "091313123121",
                    "param" => ["0", "2"]
                ),
            )
        );
        var_dump($result);
    } catch (HSException $e) {

    }
}


function demo_add_campaign()
{
    global $GATEWAY_URL, $account, $token;
    $sdk = new HSSDK(
        $GATEWAY_URL,
        $account,
        $token);

    try {
        $result = $sdk->add_campaign(
            "SKKK Campaign Test",
            2,
            "001aaab",
            new HSCampaignProperty(
                "2021-05-21 12:12:12",
                "2021-05-22 12:12:12",
                array(
                    new HSBatchSendSub(
                        "1111"
                    )
                ),
                new HSCampaignSendPeriod(
                    "12:12:12",
                    "15:12:12"
                )
            )
        );
        var_dump($result);
    } catch (HSException $e) {

    }
}


function demo_query_campaign()
{
    global $GATEWAY_URL, $account, $token;
    $sdk = new HSSDK(
        $GATEWAY_URL,
        $account,
        $token);

    try {
        $result = $sdk->query_campaign();
        var_dump($result);
    } catch (HSException $e) {

    }
}

function demo_info_campaign()
{
    global $GATEWAY_URL, $account, $token;
    $sdk = new HSSDK(
        $GATEWAY_URL,
        $account,
        $token);

    try {
        $result = $sdk->info_campaign('234234234');
        var_dump($result);
    } catch (HSException $e) {

    }
}

function demo_invalid_campaign()
{
    global $GATEWAY_URL, $account, $token;
    $sdk = new HSSDK(
        $GATEWAY_URL,
        $account,
        $token);

    try {
        $result = $sdk->invalid_campaign('234234234');
        var_dump($result);
    } catch (HSException $e) {

    }
}



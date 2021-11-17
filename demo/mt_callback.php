<?php
require_once 'CallbackHelper.php';

use HyperSMS\CallbackHelper;

//After the sending task is executed,
// the sending and downloading status
// will be callback through the callbackSendUrl specified by the access party

// The App address Provided by the accessing party,
// Generally speaking, you need to configure the url in the HyperSMS Platform OpenAPI Tab option,
// or provide it to your business manager


//Replace app key with that of your OpenAPI account
$APP_KEY = '********************';
//Whether to verify the signature of the callback request
$VALID_SIGN = true;

$body_raw = file_get_contents('php://input');

if (empty($body_raw)) {
    header('HTTP/1.1 400 Bad Request');
    return;
}

$req_data = json_decode($body_raw, true);
if ($req_data) {
    if ($VALID_SIGN && !CallbackHelper::info_check($req_data, $APP_KEY)) {
        die('ERROR');
    }

    // The Code is coding for tasks such as sending or downloading status.
    $mt_callback_code = $req_data['code'];
    $mt_callback_status = $req_data['status'];
    $mt_callback_type = $req_data['type'];
    //Synchronize the status of the sending or downloading status
    //.....
    CallbackHelper::response_ok();
}









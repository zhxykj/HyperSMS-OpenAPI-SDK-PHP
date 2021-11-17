<?php
require_once 'CallbackHelper.php';

use HyperSMS\CallbackHelper;

//The data that needs to be approval,
// when the approval status changes,
// the callback will be received through the callback Approve Url provided by the access party

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
    exit();
}

$req_data = json_decode($body_raw, true);
if ($req_data) {
    if ($VALID_SIGN && !CallbackHelper::info_check($req_data, $APP_KEY)) {
        die('ERROR');
    }

    // The Code is coding for tasks such as submitting templates or Brandname audits.
    $approve_callback_code = $req_data['code'];
    $approve_callback_status = $req_data['approve']['status'];
    $approve_callback_opinion = $req_data['approve']['opinion'];
    //Synchronize the status of the specified audit task
    //.....
    CallbackHelper::response_ok();
}









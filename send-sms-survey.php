<?php
require "../php/twilio-php-master/Services/Twilio.php";
require_once "authenticate.php";
require_once "survey-question.php";
 
global $AccountSid;
global $AuthToken;

$client = new Services_Twilio($AccountSid, $AuthToken);

$string = file_get_contents("test.numbers");
$json_a = json_decode($string, true);

$FromNumber = $json_a['from'];
$ToNumber = $json_a['to'];

$questionString = $question;
foreach ($choices as $key => $value) {
  $questionString .= "\n" . "Press " . $key . " for '" . $value . "'.";
}


foreach ($ToNumber as $recipientPhoneNumber) { 
  $message = $client->account->messages->sendMessage(
    $FromNumber, // From a valid Twilio number
    $ToNumber, // Text this number
    $questionString
  );
}

header('Location: ' . $_SERVER['HTTP_REFERER'])
?>

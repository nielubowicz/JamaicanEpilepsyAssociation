<?php
require "../../php/twilio-php-master/Services/Twilio.php";
require_once "authenticate.php";

global $AccountSid;
global $AuthToken;
$phoneNumberFile = "numbers.live";
$client = new Services_Twilio($AccountSid, $AuthToken);

$string = file_get_contents($phoneNumberFile);
$json_a = json_decode($string, true);

$FromNumber = $json_a['from'];
$ToNumber = $json_a['to'];

foreach ($ToNumber as $recipientPhoneNumber) { 
  $message = $client->account->messages->sendMessage(
    $FromNumber, // From a valid Twilio number
    $recipientPhoneNumber, // Text this number
    "Hello from the Jamaican Epilepsy Association!"
  );
}

header('Location: ' . $_SERVER['HTTP_REFERER'])
?>

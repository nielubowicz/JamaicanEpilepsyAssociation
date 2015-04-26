<?php
require "../php/twilio-php-master/Services/Twilio.php";
require_once "authenticate.php";

global $AccountSid;
global $AuthToken;

$client = new Services_Twilio($AccountSid, $AuthToken);

$string = file_get_contents("test.numbers");
$json_a = json_decode($string, true);

$FromNumber = $json_a['from'];
$ToNumber = $json_a['to'];

$message = $client->account->messages->sendMessage(
  $FromNumber, // From a valid Twilio number
  $ToNumber, // Text this number
  "Jamaican Epilepsy Association, Erie!"
);

header('Location: ' . $_SERVER['HTTP_REFERER'])
?>

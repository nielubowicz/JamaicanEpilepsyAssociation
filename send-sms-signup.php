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

$questionString = "Epilepsy continues to be a misunderstood, stigmatizing condition and it does not have to be! Join EpiCenter at JEA to help End the Stigma. Text SIGNUP to join!";

foreach ($ToNumber as $recipientPhoneNumber) { 
    $result = mysql_query($sql);
    $message = $client->account->messages->sendMessage(
      $FromNumber, // From a valid Twilio number
      $ToNumber, // Text this number
      $questionString
    );
}

header('Location: ' . $_SERVER['HTTP_REFERER']);
?>

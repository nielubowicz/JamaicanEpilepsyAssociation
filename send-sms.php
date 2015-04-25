<?php
 
require "/Users/chrisnielubowicz/pear/share/pear/Services/Twilio.php";
 
$string = file_get_contents("api.keys.live");
$json_a = json_decode($string, true);

$AccountSid = $json_a['ACCOUNT SID'];
$AuthToken = $json_a['AUTH TOKEN'];

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

// Display a confirmation message on the screen
echo "Sent message {$message->sid}";

?>

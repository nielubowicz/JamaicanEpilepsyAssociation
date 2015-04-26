<?php
require "../php/twilio-php-master/Services/Twilio.php";
require_once "authenticate.php";
require_once "survey-question.php";
 
global $AccountSid;
global $AuthToken;

$phoneNumberFile = "numbers.live";

$client = new Services_Twilio($AccountSid, $AuthToken);

$isSignup = 0;
$ToNumber = $_REQUEST['From'];

$digit = isset($_REQUEST['Body']) ? $_REQUEST['Body'] : null;
if (count($responses) == 1) {
  $say = $responses[0]; // return the default response if there is only one
} else if (isset($responses[$digit])) {
  $say = $responses[$digit];
} else if (strcasecmp($digit,'SIGNUP') == 0){
  $say = "Thank you for signing up with Mash Mash"; 
  $isSignup = 1;
} else {
  $say = "I didn't understand your response.";
  // TODO: send survey again
}

$string = file_get_contents($phoneNumberFile);
$json_a = json_decode($string, true);

$FromNumber = $json_a['from'];

if ($isSignup == 0) {
    $message = $client->account->messages->sendMessage(
      $FromNumber, // From a valid Twilio number
      $ToNumber, // Text this number
      $say
    );
} else {
  $knownNumbers = $json_a["to"];
  if (in_array($ToNumber, $knownNumbers)) {
    $say = "You're already signed up with Mash Mash";
  } else {
    array_push($knownNumbers, $ToNumber);
    $json_a["to"] = $knownNumbers;
    file_put_contents($phoneNumberFile,json_encode($json_a));
  }
  $message = $client->account->messages->sendMessage(
    $FromNumber, // From a valid Twilio number
    $ToNumber, // Text this number
    $say
  );
}

?>

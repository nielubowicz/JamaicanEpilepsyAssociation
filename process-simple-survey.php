<?php
require "../php/twilio-php-master/Services/Twilio.php";
require_once "authenticate.php";
require_once "survey-question.php";
 
global $AccountSid;
global $AuthToken;

$client = new Services_Twilio($AccountSid, $AuthToken);

	$ToNumber = $_REQUEST['From'];

	$digit = isset($_REQUEST['Body']) ? $_REQUEST['Body'] : null;
	if (count($responses) == 1) {
	  $say = $responses[0]; // return the default response if there is only one
	} else if (isset($responses[$digit])) {
	  $say = $responses[$digit];
	} else {
	  $say = "I didn't understand your response.";
	  // TODO: send survey again
	}

	$string = file_get_contents("test.numbers");
	$json_a = json_decode($string, true);

	$FromNumber = $json_a['from'];

	$message = $client->account->messages->sendMessage(
	  $FromNumber, // From a valid Twilio number
	  $ToNumber, // Text this number
	  $say
	);
?>

<?php
require_once "config.php";

    function sendMessageToUsers($message, $UserPhoneNumbers, $TWILIO_CONFIG) {
        $FromNumber = $TWILIO_CONFIG['OutgoingNumber'];
        $client = new Services_Twilio($TWILIO_CONFIG['AccountSid'], $TWILIO_CONFIG['AuthToken']);

        foreach ($UserPhoneNumbers as $ToNumber) {
            $message = $client->account->messages->sendMessage(
              $FromNumber, // From a valid Twilio number
              $ToNumber, // Text this number
              $message
            );
        }
    }

?>

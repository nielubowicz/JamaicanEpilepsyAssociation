<?php
require_once "config.php";
require_once "send-sms-information.php";

// Processing functions: includes signup and survey responses
    function processSignup($UserPhoneNumber, $sql_connection) {


        $sql = "SELECT * FROM users WHERE PhoneNumber='" . $UserPhoneNumber . "';"; 
        $result = $sql_connection->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc(); 
            $Status = $row['Status'];
            switch ($Status) {
                case -1:
                case 0: 
                case 1:
                    $say = "Thanks for registering with EpiCentral!";
                break;
                case 2:
                    $say = "You're already signed up with EpiCentral";
                break;
            }
        } else {
            echo "No numbers found to send signup message\n";
        }

        $sql = "UPDATE users SET Status=2 WHERE PhoneNumber='" . $UserPhoneNumber . "';";
        $sql_connection->query($sql);
        $sql_connection->close();
        return $say;
    }

    function processLeave ($UserPhoneNumber, $sql_connection) {
        $sql = "SELECT * FROM users WHERE PhoneNumber='" . $UserPhoneNumber . "';"; 
        $result = $sql_connection->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc(); 
            $Status = $row['Status'];
            switch ($Status) {
                case -1:
                    $message = "You have already been removed from EpiCentral.";
                    break;
                case 0: 
                    $message = "You were never signed up for EpiCentral. Doing nothing.";
                break;
                case 1:
                case 2:
                    $message = "You have been removed from the EpiCentral SMS list. We're sad to see you go!";
                break;
            }
        } else {
            echo "No numbers found to send signup message\n";
        }

        $sql = "UPDATE users SET Status=-1 WHERE PhoneNumber='" . $UserPhoneNumber . "';";
        $sql_connection->query($sql);
        $sql_connection->close();
        return $message;
    }

    // Create connection
    $sql_connection = new mysqli($SQL_JEA_CONFIG['servername'], $SQL_JEA_CONFIG['username'], $SQL_JEA_CONFIG['password'], $SQL_JEA_CONFIG['db']);
    // Check connection
    if ($sql_connection->connect_error) {
        die("Connection failed: " . $sql_connection->connect_error);
    } 

    $FromNumber = $TWILIO_JEA_CONFIG['OutgoingNumber'];
    $client = new Services_Twilio($TWILIO_JEA_CONFIG['AccountSid'], $TWILIO_JEA_CONFIG['AuthToken']);

    $ToNumber = isset($_REQUEST['From']) ? $_REQUEST['From'] : null;
    $digit = isset($_REQUEST['Body']) ? $_REQUEST['Body'] : null;
    if (strcasecmp($digit,'SIGNUP') == 0){
        $messageText = processSignup($ToNumber, $sql_connection);
    } else if (strcasecmp($digit,'LEAVE') == 0){
        $messageText = processLeave($ToNumber, $sql_connection);
    } else if (strcasecmp($digit,'MESSAGE') == 0){
        processInfoMessage();
        $messageText = "Your MESSAGE blast will be delivered momentarily";
    } else if (strcasecmp($digit,'MESSAGE ME') == 0){
        $messageText = getRandomMessage($sql_connection);
    } else if (strcasecmp($digit,'SURVEY') == 0){
        $messageText = "SURVEY is not supported yet.";
    } else {
        // TODO: process other things
        $messageText = "Cannot understand your response. The only supported actions are SIGNUP, MESSAGE, MESSAGE ME, and SURVEY.";
    }

    if (isset($messageText) && isset($ToNumber)) {
        $message = $client->account->messages->sendMessage(
            $FromNumber, // From a valid Twilio number
            $ToNumber, // Text this number
            $messageText
        );
    }
?>

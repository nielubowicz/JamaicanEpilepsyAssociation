<?php
require "../../php/twilio-php-master/Services/Twilio.php";
require_once "config.php";

    $FromNumber = $TWILIO_JEA_CONFIG['OutgoingNumber'];
    $client = new Services_Twilio($TWILIO_JEA_CONFIG['AccountSid'], $TWILIO_JEA_CONFIG['AuthToken']);
    $questionString = "Epilepsy continues to be a misunderstood, stigmatizing condition and it does not have to be! Join EpiCenter at JEA to help End the Stigma. Text SIGNUP to join!";

    // Create connection
    $sql_connection = new mysqli($SQL_JEA_CONFIG['servername'], $SQL_JEA_CONFIG['username'], $SQL_JEA_CONFIG['password'], $SQL_JEA_CONFIG['db']);
    // Check connection
    if ($sql_connection->connect_error) {
        die("Connection failed: " . $sql_connection->connect_error);
    } 

    $sql = "SELECT * FROM signups WHERE Status=0"; // select interested, uncontacted people
    $result = $sql_connection->query($sql);

    $ToNumbers = array();
    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            $ToNumber = $row['PhoneNumber'];
            array_push($ToNumbers, "'".$ToNumber."'");
            $message = $client->account->messages->sendMessage(
              $FromNumber, // From a valid Twilio number
              $ToNumber, // Text this number
              $questionString
            );
        }
    } else {
        echo "No numbers found to send signup message\n";
    }

    $sql = "UPDATE signups SET Status=1 WHERE PhoneNumber IN (" . implode(",", $ToNumbers) . ");";
    $sql_connection->query($sql);
    $sql_connection->close();
header('Location: ' . $_SERVER['HTTP_REFERER']);
?>

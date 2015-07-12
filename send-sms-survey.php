<?php
require_once "config.php";
require_once "send-sms.php";

function processSendSurveyMessage() {
    global $SQL_JEA_CONFIG;
    global $TWILIO_JEA_CONFIG;

    $sql_connection = new mysqli($SQL_JEA_CONFIG['servername'], $SQL_JEA_CONFIG['username'], $SQL_JEA_CONFIG['password'], $SQL_JEA_CONFIG['db']);
    // Check connection
    if ($sql_connection->connect_error) {
        die("Connection failed: " . $sql_connection->connect_error);
    } 

    $sql = "SELECT * FROM users WHERE Status=2"; // select signed-up, interested people
    $result = $sql_connection->query($sql);

    $users = array();
    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            array_push($users, $row);
        }
    } else {
        echo "No numbers found to send survey\n";
    }

    // Find users who have not started a survey, send them a message
    $ToNumbers = array_map(function($array) {return $array['PhoneNumber'];}, $users);
    $UserIDs = array_map(function($array) {return $array['userID'];}, $users);

    $sql_connection->close();

    sendMessageToUsers($message, $ToNumbers, $TWILIO_JEA_CONFIG);
}


?>

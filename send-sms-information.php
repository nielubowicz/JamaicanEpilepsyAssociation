<?php
require_once "config.php";
require_once "send-sms.php";

function getRandomMessage($sql_connection) {
    $sql = "SELECT * FROM messages";
    $result = $sql_connection->query($sql);

    $message = array ('message' => "This is the default message",
                      'messageID' => 0);
    if ($result->num_rows > 0) {
        $messageRows = array();
        while($row = $result->fetch_assoc()) {
            array_push($messageRows, $row);
        }
        $messageRow = $messageRows[array_rand($messageRows)];
        $message['message'] = $messageRow['message'];
        $message['messageID'] = $messageRow['messageID'];
    }

    return $message;
}

function processInfoMessage() {
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
        echo "No numbers found to send message\n";
    }

    $message = getRandomMessage($sql_connection);

    // Save a record of this message to these users to the users_messages table
    $sql = "";
    foreach ($users as $user) {
        $sql .= "INSERT INTO users_messages (userID, messageID) VALUES (" . $user['userID'] . "," . $message['messageID'] . ");\n";
    }
    $sql_connection->query($sql);
    $sql_connection->close();

    $ToNumbers = array_map(function($array) {return $array['PhoneNumber'];}, $users);
    sendMessageToUsers($message['message'], $ToNumbers, $TWILIO_JEA_CONFIG);
}

?>

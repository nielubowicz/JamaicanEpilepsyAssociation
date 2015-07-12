<?php

// DOCUMENT_ROOT returns the path for ~/www/ directory
if (empty($_SERVER['DOCUMENT_ROOT'])) {
    $twilio_path = $_SERVER['HOME'] ."/php/twilio-php-master/Services/Twilio.php";
} else {
    $twilio_path = $_SERVER['DOCUMENT_ROOT'] ."/../php/twilio-php-master/Services/Twilio.php";
}
include_once($twilio_path);

if (empty($_SERVER['DOCUMENT_ROOT'])) {
    $config_path = $_SERVER['HOME'] ."/configs/jamaicanepilepsyassociation.com";
} else {
    $config_path = $_SERVER['DOCUMENT_ROOT'] ."/../configs/jamaicanepilepsyassociation.com";
}

    $sql_config = $config_path ."/sql_config.php";
    $twilio_config = $config_path ."/twilio_config.php";

    include_once($sql_config);
    include_once($twilio_config);
?>

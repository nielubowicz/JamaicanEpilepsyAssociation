<?php

    $path = $_SERVER['HOME'] ."/configs/jamaicanepilepsyassociation.com";
    $sql_config = $path ."/sql_config.php";
    $twilio_config = $path ."/twilio_config.php";

    include_once($sql_config);
    include_once($twilio_config);
?>

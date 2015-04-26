<?php

$string = file_get_contents("api.keys.live");
$json_a = json_decode($string, true);

$AccountSid = $json_a['ACCOUNT SID'];
$AuthToken = $json_a['AUTH TOKEN'];

?>

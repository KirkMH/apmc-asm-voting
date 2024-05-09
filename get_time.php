<?php

date_default_timezone_set('Asia/Manila');

$timestamp = time();
$date_time = date("F j, Y, g:i:s a", $timestamp);
echo $date_time;

?>